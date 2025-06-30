<?php
// ¡ATENCIÓN!
// Cambia la URL de la API por la real de tu proveedor de competencia.
// Puedes probar la ejecución manual del script así:
//
// pwsh.exe -Command "php c:/xampp/htdocs/Obelis-Store/php_call_dynamic_price.php"
//
// Para automatizarlo diariamente a las 2:00 AM, ejecuta en PowerShell como administrador:
//
// $Action = New-ScheduledTaskAction -Execute 'php.exe' -Argument 'c:/xampp/htdocs/Obelis-Store/php_call_dynamic_price.php'
// $Trigger = New-ScheduledTaskTrigger -Daily -At 2:00AM
// $Principal = New-ScheduledTaskPrincipal -UserId $env:USERNAME -LogonType Interactive
// Register-ScheduledTask -TaskName 'ActualizarPreciosObelisStore' -Action $Action -Trigger $Trigger -Principal $Principal -Description 'Actualiza precios dinámicos de la tienda Obelis Store cada noche'
//
// Puedes consultar el historial de precios de la competencia en la tabla precios_competencia.

// Ejemplo de cómo llamar al microservicio Python desde PHP
// Ejemplo profesional: obtén datos reales de tu base de datos/producto
$producto_id = 123;
$precio_base = 120.0;
$demanda = 8; // ventas recientes
$inventario = 30; // stock actual
$precio_competencia = 115.0; // obtenido por scraping/API
$tendencia = 1.03; // 1.0 = neutra, >1.0 = positiva, <1.0 = negativa
$estacionalidad = 0.97; // 1.0 = neutra, >1.0 = alta, <1.0 = baja
$precio_anterior = 118.0; // Simulación: precio anterior (puedes obtenerlo de la BD)

// --- Obtención real del precio anterior desde la base de datos (tabla productos, campo precio) ---
require_once __DIR__ . '/Config/Config.php';
$conn = new mysqli(HOST, USER, PASS, DB);
if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}
$sql = "SELECT precio FROM productos WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $producto_id);
$stmt->execute();
$stmt->bind_result($precio_anterior_db);
if ($stmt->fetch()) {
    $precio_anterior = $precio_anterior_db;
}
$stmt->close();
$conn->close();

$data = [
    'producto_id' => $producto_id,
    'precio_base' => $precio_base,
    'demanda' => $demanda,
    'inventario' => $inventario,
    'precio_competencia' => $precio_competencia,
    'tendencia' => $tendencia,
    'estacionalidad' => $estacionalidad,
    'precio_anterior' => $precio_anterior
];
$options = [
    'http' => [
        'header'  => "Content-type: application/json",
        'method'  => 'POST',
        'content' => json_encode($data),
        'timeout' => 5
    ],
];
$context  = stream_context_create($options);
$result = file_get_contents('http://localhost:5000/precio-dinamico', false, $context);
$respuesta = json_decode($result, true);
if (isset($respuesta['precio'])) {
    $precio_dinamico = $respuesta['precio'];
    $precio_anterior = $respuesta['precio_anterior'] ?? 'N/A';
    $cambio_pct = $respuesta['cambio_pct'] ?? 'N/A';
    echo "Precio anterior: $precio_anterior\n";
    echo "Precio dinámico calculado: $precio_dinamico\n";
    echo "Cambio porcentual: $cambio_pct%\n";
    if (isset($respuesta['log'])) {
        echo "\n--- Detalle de la lógica aplicada ---\n";
        foreach ($respuesta['log'] as $linea) {
            echo $linea . "\n";
        }
    }
} else {
    echo "Error: " . ($respuesta['error'] ?? 'No se pudo calcular el precio') . "\n";
    if (isset($respuesta['detalle'])) {
        echo "Detalle: " . $respuesta['detalle'] . "\n";
    }
}

// === Automatización total de precios dinámicos para Obelis Store ===
// Este script recorre todos los productos activos, llama al microservicio y actualiza el precio en la BD.
// Puedes programarlo como tarea automática en Windows.

require_once __DIR__ . '/Config/Config.php';
$conn = new mysqli(HOST, USER, PASS, DB);
if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// --- FUNCIONES AUXILIARES ---
function obtenerDemanda($producto_id, $conn) {
    // Ejemplo: ventas de los últimos 7 días
    $sql = "SELECT SUM(cantidad) as ventas FROM detalle_pedidos WHERE id_producto = ? AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $stmt->bind_result($ventas);
    $stmt->fetch();
    $stmt->close();
    return $ventas ?: 0;
}
use GuzzleHttp\Client;
use DiDom\Document;
require_once __DIR__ . '/vendor/autoload.php';

function obtenerPrecioCompetencia($producto_id) {
    global $conn;
    // Cambia la siguiente URL por la de tu proveedor real de precios de competencia
    // Ejemplo: https://api.tuproveedor.com/precios/ID o https://api.tuproveedor.com/precios?producto_id=ID
    $api_url = "https://api.tuproveedor.com/precios/$producto_id";
    $client = new Client([
        'timeout' => 5.0,
        'http_errors' => false
    ]);
    try {
        $response = $client->request('GET', $api_url);
        $status = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        if ($status === 200) {
            $data = json_decode($body, true);
            if (isset($data['precio'])) {
                // Guarda el precio en la tabla de competencia
                $fuente = isset($data['fuente']) ? $data['fuente'] : 'api.competidor.com';
                $stmt = $conn->prepare("INSERT INTO precios_competencia (producto_id, fuente, precio) VALUES (?, ?, ?)");
                $stmt->bind_param('isd', $producto_id, $fuente, $data['precio']);
                $stmt->execute();
                $stmt->close();
                return $data['precio'];
            }
        }
    } catch (Exception $e) {
        // Manejo de excepciones
        error_log("Error en obtenerPrecioCompetencia: " . $e->getMessage());
    }
    // Si falla la API, consulta el último precio guardado
    $stmt = $conn->prepare("SELECT precio FROM precios_competencia WHERE producto_id = ? ORDER BY fecha DESC LIMIT 1");
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $stmt->bind_result($precio);
    if ($stmt->fetch()) {
        $stmt->close();
        return $precio;
    }
    $stmt->close();
    return 0;
}
function calcularTendencia($producto_id, $conn) {
    // Ejemplo: ventas últimos 7 días vs. 30 días
    $sql = "SELECT SUM(cantidad) as ventas7 FROM detalle_pedidos WHERE id_producto = ? AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $stmt->bind_result($ventas7);
    $stmt->fetch();
    $stmt->close();
    $sql = "SELECT SUM(cantidad) as ventas30 FROM detalle_pedidos WHERE id_producto = ? AND fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $stmt->bind_result($ventas30);
    $stmt->fetch();
    $stmt->close();
    if ($ventas30 > 0) {
        return round($ventas7 / ($ventas30 / 4), 2); // tendencia semanal vs. promedio semanal de 30 días
    }
    return 1.0;
}
function calcularEstacionalidad($producto_id) {
    // Puedes usar un calendario, API externa o lógica propia
    // Ejemplo: 1.0 (neutro)
    return 1.0;
}

// --- PROCESO PRINCIPAL ---
$sql = "SELECT id, precio, cantidad FROM productos WHERE estado = 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $producto_id = $row['id'];
        $precio_base = $row['precio'];
        $inventario = $row['cantidad'];
        $demanda = obtenerDemanda($producto_id, $conn);
        $precio_competencia = obtenerPrecioCompetencia($producto_id);
        if ($precio_competencia <= 0) $precio_competencia = $precio_base * 0.98; // fallback
        $tendencia = calcularTendencia($producto_id, $conn);
        $estacionalidad = calcularEstacionalidad($producto_id);
        $precio_anterior = $precio_base;
        $data = [
            'producto_id' => $producto_id,
            'precio_base' => $precio_base,
            'demanda' => $demanda,
            'inventario' => $inventario,
            'precio_competencia' => $precio_competencia,
            'tendencia' => $tendencia,
            'estacionalidad' => $estacionalidad,
            'precio_anterior' => $precio_anterior
        ];
        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 10
            ],
        ];
        $context  = stream_context_create($options);
        $result_api = @file_get_contents('http://localhost:5000/precio-dinamico', false, $context);
        $respuesta = json_decode($result_api, true);
        if (isset($respuesta['precio'])) {
            $precio_dinamico = $respuesta['precio'];
            // Actualiza el precio en la base de datos
            $update = $conn->prepare("UPDATE productos SET precio = ? WHERE id = ?");
            $update->bind_param('di', $precio_dinamico, $producto_id);
            $update->execute();
            $update->close();
            echo "Producto $producto_id actualizado: $precio_base -> $precio_dinamico\n";
        } else {
            echo "Error al actualizar producto $producto_id\n";
        }
    }
} else {
    echo "No hay productos para actualizar.\n";
}
$conn->close();

// === INSTRUCCIONES PARA TAREA AUTOMÁTICA ===
// Ejecuta este script con:
//   php C:\xampp\htdocs\Obelis-Store\php_call_dynamic_price.php
// Puedes programarlo con el siguiente comando en PowerShell (ajusta la ruta de php.exe si es necesario):
//
// $Action = New-ScheduledTaskAction -Execute "C:\xampp\php\php.exe" -Argument "C:\xampp\htdocs\Obelis-Store\php_call_dynamic_price.php"
// $Trigger = New-ScheduledTaskTrigger -Daily -At 2:00AM
// $Principal = New-ScheduledTaskPrincipal -UserId "$env:USERNAME" -LogonType Interactive
// Register-ScheduledTask -TaskName "ActualizarPreciosObelisStore" -Action $Action -Trigger $Trigger -Principal $Principal -Description "Actualiza precios dinámicos de la tienda Obelis Store cada noche"

