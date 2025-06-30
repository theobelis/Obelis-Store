<?php
class ProductosModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }

    // Método principal para obtener productos filtrados
    public function getProductosFiltrados($filtros = [], $limite = 12, $offset = 0)
    {
        error_log("Llamada a getProductosFiltrados con filtros: " . print_r($filtros, true));
        
        $sql = "SELECT p.*, 
                c.categoria,
                IFNULL((SELECT AVG(calificacion) FROM calificaciones_producto WHERE id_producto = p.id), 0) as calificacion,
                (SELECT COUNT(*) FROM calificaciones_producto WHERE id_producto = p.id) as total_reviews,
                (SELECT GROUP_CONCAT(DISTINCT color) FROM colores_producto WHERE id_producto = p.id) as colores,
                (SELECT GROUP_CONCAT(DISTINCT talla) FROM tallas_producto WHERE id_producto = p.id) as tallas
                FROM productos p
                LEFT JOIN categorias c ON p.id_categoria = c.id
                WHERE p.estado = 1";
        
        $params = [];
        
        if (!empty($filtros['destacado'])) {
            $sql .= " AND p.destacado = 1";
        }
        
        if (!empty($filtros['categoria'])) {
            $sql .= " AND p.id_categoria = ?";
            $params[] = $filtros['categoria'];
        }

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $busqueda = "%{$filtros['busqueda']}%";
            $params[] = $busqueda;
            $params[] = $busqueda;
        }

        if (!empty($filtros['oferta'])) {
            $sql .= " AND p.precio_oferta IS NOT NULL AND p.precio_oferta > 0";
        }
        
        // Ordenamiento
        switch ($filtros['ordenar_por'] ?? 'reciente') {
            case 'precio_asc':
                $sql .= " ORDER BY p.precio ASC";
                break;
            case 'precio_desc':
                $sql .= " ORDER BY p.precio DESC";
                break;
            case 'calificacion':
                $sql .= " ORDER BY calificacion DESC, total_reviews DESC";
                break;
            case 'destacado':
                $sql .= " ORDER BY p.destacado DESC, calificacion DESC";
                break;
            case 'aleatorio':
                $sql .= " ORDER BY RAND()";
                break;
            case 'mayor_descuento':
                $sql .= " ORDER BY ((p.precio - p.precio_oferta) / p.precio) DESC";
                break;
            case 'reciente':
            default:
                $sql .= " ORDER BY p.id DESC";
        }
        
        if ($limite) {
            $sql .= " LIMIT " . (int)$offset . ", " . (int)$limite;
        }

        error_log("SQL Query: " . $sql);
        error_log("Params: " . print_r($params, true));

        $result = $this->selectAll($sql, $params);
        
        // Procesar los resultados para asegurar que las URLs de las imágenes sean correctas
        foreach ($result as &$producto) {
            if (!empty($producto['imagen'])) {
                if (!filter_var($producto['imagen'], FILTER_VALIDATE_URL)) {
                    // Si no es una URL completa, añadir la ruta base
                    $producto['imagen'] = empty($producto['imagen']) ? 
                        BASE_URL . 'assets/images/product-placeholder.jpg' : 
                        BASE_URL . 'assets/images/productos/' . $producto['imagen'];
                }
            } else {
                $producto['imagen'] = BASE_URL . 'assets/images/product-placeholder.jpg';
            }

            // Convertir strings de colores y tallas en arrays
            $producto['colores'] = !empty($producto['colores']) ? explode(',', $producto['colores']) : [];
            $producto['tallas'] = !empty($producto['tallas']) ? explode(',', $producto['tallas']) : [];
        }

        error_log("Resultado de getProductosFiltrados: " . print_r($result, true));
        return $result;
    }
    
    public function getProductosDestacados($limite = 12)
    {
        error_log("Obteniendo productos destacados...");
        return $this->getProductosFiltrados(['destacado' => true, 'ordenar_por' => 'destacado'], $limite);
    }
    
    public function getProductosAleatorios($limite = 4)
    {
        return $this->getProductosFiltrados(['ordenar_por' => 'aleatorio'], $limite);
    }
    
    public function getProductosMejorValorados($limite = 4)
    {
        return $this->getProductosFiltrados(['ordenar_por' => 'calificacion'], $limite);
    }
    
    public function getNuevosProductos($limite = 8)
    {
        return $this->getProductosFiltrados(['ordenar_por' => 'reciente'], $limite);
    }
    
    public function getProductosEnOferta($limite = 8)
    {
        return $this->getProductosFiltrados([
            'oferta' => true,
            'ordenar_por' => 'mayor_descuento'
        ], $limite);
    }

    public function getProductos($estado)
    {
        $sql = "SELECT * FROM productos WHERE estado = $estado";
        return $this->selectAll($sql);
    }
    public function getCategorias()
    {
        $sql = "SELECT * FROM categorias WHERE estado = 1";
        return $this->selectAll($sql);
    }

    public function registrar($nombre, $descripcion, $precio, $cantidad, $imagen, $categoria, $destacado)
    {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen, id_categoria, destacado) VALUES (?,?,?,?,?,?,?)";
        $array = array($nombre, $descripcion, $precio, $cantidad, $imagen, $categoria, $destacado);
        return $this->insertar($sql, $array);
    }

    public function eliminar($idPro)
    {
        $sql = "UPDATE productos SET estado = ? WHERE id = ?";
        $array = array(0, $idPro);
        return $this->save($sql, $array);
    }

    public function getProducto($idPro)
    {
        $sql = "SELECT p.*, c.categoria 
                FROM productos p 
                INNER JOIN categorias c ON p.id_categoria = c.id 
                WHERE p.id = ? AND p.estado = 1";
        $result = $this->select($sql, [$idPro]);
        if (!empty($result)) {
            // Procesar imagen
            if (!empty($result['imagen'])) {
                if (!filter_var($result['imagen'], FILTER_VALIDATE_URL)) {
                    $result['imagen'] = BASE_URL . 'assets/images/productos/' . $result['imagen'];
                }
            } else {
                $result['imagen'] = BASE_URL . 'assets/images/product-placeholder.jpg';
            }
        }
        return $result;
    }

    public function modificar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $destacado, $id)
    {
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, cantidad=?, imagen=?, id_categoria=?, destacado=? WHERE id = ?";
        $array = array($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $destacado, $id);
        return $this->save($sql, $array);
    }
    // Obtener colores de un producto
    public function getColores($id_producto)
    {
        $sql = "SELECT color FROM colores_producto WHERE id_producto = ?";
        $colores = $this->selectAll($sql, [$id_producto]);
        return array_column($colores, 'color');
    }

    // Guardar colores de un producto
    public function guardarColores($id_producto, $colores)
    {
        $this->query("DELETE FROM colores_producto WHERE id_producto = ?", [$id_producto]);
        if (!empty($colores)) {
            $colores = array_unique(array_filter(array_map('trim', $colores)));
            foreach ($colores as $color) {
                if (preg_match('/^#[0-9A-Fa-f]{6}$|^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,20}$/', $color)) {
                    $this->insertar("INSERT INTO colores_producto (id_producto, color) VALUES (?, ?)", [$id_producto, $color]);
                }
            }
        }
    }

    // Obtener tallas de un producto
    public function getTallas($id_producto)
    {
        $sql = "SELECT talla FROM tallas_producto WHERE id_producto = ?";
        $tallas = $this->selectAll($sql, [$id_producto]);
        return array_column($tallas, 'talla');
    }

    // Guardar tallas de un producto
    public function guardarTallas($id_producto, $tallas)
    {
        $this->query("DELETE FROM tallas_producto WHERE id_producto = ?", [$id_producto]);
        if (!empty($tallas)) {
            $tallas = array_unique(array_filter(array_map('trim', $tallas)));
            foreach ($tallas as $talla) {
                if (preg_match('/^[A-Za-z0-9\-\+]{1,10}$/', $talla)) {
                    $this->insertar("INSERT INTO tallas_producto (id_producto, talla) VALUES (?, ?)", [$id_producto, $talla]);
                }
            }
        }
    }

    // Obtener calificaciones de un producto
    public function getCalificaciones($id_producto)
    {
        $sql = "SELECT calificacion FROM calificaciones_producto WHERE id_producto = ?";
        $calificaciones = $this->selectAll($sql, [$id_producto]);
        if (!empty($calificaciones)) {
            return array_map(function($c) {
                return ['calificacion' => $c['calificacion']];
            }, $calificaciones);
        }
        return [];
    }

    // Guardar calificación de un producto
    public function guardarCalificacion($id_producto, $calificacion, $comentario = null)
    {
        return $this->insertar(
            "INSERT INTO calificaciones_producto (id_producto, calificacion, comentario) VALUES (?, ?, ?)", 
            [$id_producto, $calificacion, $comentario]
        );
    }

    // Guardar review completo
    public function guardarReview($id_producto, $id_cliente, $nombre_cliente, $calificacion, $comentario)
    {
        $sql = "INSERT INTO calificaciones_producto (id_producto, id_cliente, nombre_cliente, calificacion, comentario, fecha) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        return $this->insertar($sql, [$id_producto, $id_cliente, $nombre_cliente, $calificacion, $comentario]);
    }

    // Obtener todos los reviews de un producto
    public function getReviews($id_producto)
    {
        $sql = "SELECT cp.*, COALESCE(c.nombre, cp.nombre_cliente) as cliente 
                FROM calificaciones_producto cp 
                LEFT JOIN clientes c ON cp.id_cliente = c.id 
                WHERE cp.id_producto = ? AND cp.comentario IS NOT NULL 
                ORDER BY cp.fecha DESC";
        return $this->selectAll($sql, [$id_producto]);
    }
    // Obtener promedio de calificación de un producto
    public function getPromedioReview($id_producto)
    {
        $sql = "SELECT AVG(calificacion) as promedio FROM producto_reviews WHERE id_producto = ?";
        $result = $this->select($sql, [$id_producto]);
        return $result ? $result['promedio'] : 0;
    }
    // Validar si el usuario ya dejó review para el producto
    public function yaReview($id_producto, $id_cliente)
    {
        $sql = "SELECT id FROM calificaciones_producto WHERE id_producto = ? AND id_cliente = ?";
        $res = $this->select($sql, [$id_producto, $id_cliente]);
        return !empty($res);
    }

    public function getGaleria($id_producto)
    {
        $result = array();
        $directorio = 'assets/images/productos/' . $id_producto;
        if (file_exists($directorio)) {
            $imagenes = scandir($directorio);
            if (false !== $imagenes) {
                foreach ($imagenes as $file) {
                    if ('.' != $file && '..' != $file) {
                        $result[] = $file;
                    }
                }
            }
        }
        return $result;
    }
}
 
?>