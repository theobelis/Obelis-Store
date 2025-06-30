from flask import Flask, request, jsonify
import logging
import os
from datetime import datetime

app = Flask(__name__)

# Configuración de logging profesional
def setup_logger():
    logger = logging.getLogger("dynamic_price")
    logger.setLevel(logging.INFO)
    # Carpeta de logs
    log_dir = os.path.join(os.path.dirname(__file__), "logs")
    if not os.path.exists(log_dir):
        os.makedirs(log_dir)
    # Log único por sesión para evitar conflictos
    log_file = os.path.join(log_dir, f"microservicio_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log")
    fh = logging.FileHandler(log_file)
    fh.setLevel(logging.INFO)
    formatter = logging.Formatter('%(asctime)s - %(levelname)s - %(message)s')
    fh.setFormatter(formatter)
    logger.addHandler(fh)
    return logger

logger = setup_logger()

# Parámetros de negocio
MIN_PRECIO = 10.0
MAX_PRECIO = 10000.0
UMBRAL_BAJA_DEMANDA = 5
UMBRAL_ALTA_DEMANDA = 20
UMBRAL_BAJO_STOCK = 10
UMBRAL_ALTO_STOCK = 100
MARGEN_COMPETENCIA = 0.98  # 2% más barato que la competencia
# Ponderaciones profesionales (pueden venir de BD/config en producción)
PONDERACION = {
    'demanda': 0.25,
    'inventario': 0.20,
    'competencia': 0.30,
    'tendencia': 0.15,
    'estacionalidad': 0.10
}
MAX_VARIACION = 0.15  # Máximo cambio permitido por ciclo (15%)
REGLAS_PERSONALIZADAS = {
    # 'producto_id': {'min': 20, 'max': 500, ...}
}

@app.route('/precio-dinamico', methods=['POST'])
def precio_dinamico():
    try:
        data = request.json
        producto_id = data.get('producto_id', 'N/A')
        precio_base = float(data.get('precio_base', 100))
        demanda = float(data.get('demanda', 10))
        inventario = float(data.get('inventario', 50))
        precio_competencia = float(data.get('precio_competencia', precio_base))
        tendencia = float(data.get('tendencia', 1.0))
        estacionalidad = float(data.get('estacionalidad', 1.0))
        precio_anterior = float(data.get('precio_anterior', precio_base))

        # Reglas personalizadas por producto
        min_precio = REGLAS_PERSONALIZADAS.get(producto_id, {}).get('min', MIN_PRECIO)
        max_precio = REGLAS_PERSONALIZADAS.get(producto_id, {}).get('max', MAX_PRECIO)

        log_msgs = [f"Producto: {producto_id} | Precio base: {precio_base}"]
        ajustes = []
        precio = precio_base

        # 1. Ajuste por demanda
        ajuste_demanda = 0.0
        if demanda < UMBRAL_BAJA_DEMANDA:
            ajuste_demanda = -0.07 * PONDERACION['demanda']
            log_msgs.append(f"Demanda baja ({demanda}), ajuste ponderado: {ajuste_demanda*100:.2f}%.")
        elif demanda > UMBRAL_ALTA_DEMANDA:
            ajuste_demanda = 0.12 * PONDERACION['demanda']
            log_msgs.append(f"Demanda alta ({demanda}), ajuste ponderado: {ajuste_demanda*100:.2f}%.")
        else:
            log_msgs.append(f"Demanda normal ({demanda}), sin ajuste.")
        ajustes.append(ajuste_demanda)

        # 2. Ajuste por inventario
        ajuste_inventario = 0.0
        if inventario < UMBRAL_BAJO_STOCK:
            ajuste_inventario = 0.08 * PONDERACION['inventario']
            log_msgs.append(f"Inventario bajo ({inventario}), ajuste ponderado: {ajuste_inventario*100:.2f}%.")
        elif inventario > UMBRAL_ALTO_STOCK:
            ajuste_inventario = -0.05 * PONDERACION['inventario']
            log_msgs.append(f"Inventario alto ({inventario}), ajuste ponderado: {ajuste_inventario*100:.2f}%.")
        else:
            log_msgs.append(f"Inventario normal ({inventario}), sin ajuste.")
        ajustes.append(ajuste_inventario)

        # 3. Ajuste por competencia
        ajuste_competencia = 0.0
        if precio_competencia > 0 and precio > precio_competencia:
            ajuste_competencia = ((precio_competencia * MARGEN_COMPETENCIA) / precio - 1) * PONDERACION['competencia']
            log_msgs.append(f"Precio competencia ({precio_competencia}), ajuste ponderado: {ajuste_competencia*100:.2f}%.")
        else:
            log_msgs.append(f"Precio competencia ({precio_competencia}), sin ajuste.")
        ajustes.append(ajuste_competencia)

        # 4. Ajuste por tendencia
        ajuste_tendencia = 0.0
        if tendencia > 1.05:
            ajuste_tendencia = 0.04 * PONDERACION['tendencia']
            log_msgs.append(f"Tendencia positiva ({tendencia}), ajuste ponderado: {ajuste_tendencia*100:.2f}%.")
        elif tendencia < 0.95:
            ajuste_tendencia = -0.04 * PONDERACION['tendencia']
            log_msgs.append(f"Tendencia negativa ({tendencia}), ajuste ponderado: {ajuste_tendencia*100:.2f}%.")
        else:
            log_msgs.append(f"Tendencia neutra ({tendencia}), sin ajuste.")
        ajustes.append(ajuste_tendencia)

        # 5. Ajuste por estacionalidad
        ajuste_estacionalidad = 0.0
        if estacionalidad > 1.05:
            ajuste_estacionalidad = 0.06 * PONDERACION['estacionalidad']
            log_msgs.append(f"Estacionalidad alta ({estacionalidad}), ajuste ponderado: {ajuste_estacionalidad*100:.2f}%.")
        elif estacionalidad < 0.95:
            ajuste_estacionalidad = -0.06 * PONDERACION['estacionalidad']
            log_msgs.append(f"Estacionalidad baja ({estacionalidad}), ajuste ponderado: {ajuste_estacionalidad*100:.2f}%.")
        else:
            log_msgs.append(f"Estacionalidad neutra ({estacionalidad}), sin ajuste.")
        ajustes.append(ajuste_estacionalidad)

        # Suma de ajustes ponderados
        total_ajuste = sum(ajustes)
        log_msgs.append(f"Ajuste total ponderado: {total_ajuste*100:.2f}%.")
        precio_nuevo = precio * (1 + total_ajuste)

        # Protección ante cambios bruscos
        variacion = (precio_nuevo - precio_anterior) / precio_anterior if precio_anterior else 0
        if variacion > MAX_VARIACION:
            log_msgs.append(f"Variación limitada a +{MAX_VARIACION*100:.2f}% (antes: {variacion*100:.2f}%).")
            precio_nuevo = precio_anterior * (1 + MAX_VARIACION)
        elif variacion < -MAX_VARIACION:
            log_msgs.append(f"Variación limitada a -{MAX_VARIACION*100:.2f}% (antes: {variacion*100:.2f}%).")
            precio_nuevo = precio_anterior * (1 - MAX_VARIACION)
        else:
            log_msgs.append(f"Variación permitida: {variacion*100:.2f}%.")

        # Límite de precios
        if precio_nuevo < min_precio:
            log_msgs.append(f"Precio por debajo del mínimo ({min_precio}), ajustando.")
            precio_nuevo = min_precio
        elif precio_nuevo > max_precio:
            log_msgs.append(f"Precio por encima del máximo ({max_precio}), ajustando.")
            precio_nuevo = max_precio
        else:
            log_msgs.append(f"Precio dentro de límites.")

        precio_final = round(precio_nuevo, 2)
        cambio_pct = ((precio_final - precio_anterior) / precio_anterior * 100) if precio_anterior else 0
        log_msgs.append(f"Precio anterior: {precio_anterior} | Precio final: {precio_final} | Cambio: {cambio_pct:.2f}%")
        logger.info(' | '.join(log_msgs))
        # Guardar historial estructurado (opcional, aquí solo log)
        return jsonify({
            'precio': precio_final,
            'precio_anterior': precio_anterior,
            'cambio_pct': round(cambio_pct, 2),
            'log': log_msgs
        })
    except Exception as e:
        logger.error(f"Error en precio-dinamico: {str(e)}")
        return jsonify({'error': 'Error interno en el microservicio', 'detalle': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
