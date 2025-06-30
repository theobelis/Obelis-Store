<?php
class PrincipalModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getProducto($id_producto)
    {
        $sql = "SELECT p.*, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.id = $id_producto";
        return $this->select($sql);
    }
    //busqueda de productos
    public function getBusqueda($valor)
    {
        $sql = "SELECT * FROM productos WHERE nombre LIKE '%". $valor."%' OR descripcion LIKE '%". $valor."%' LIMIT 5";
        return $this->selectAll($sql);
    }
    // Obtener productos aleatorios
    public function getProductosAleatorios($limite)
    {
        $sql = "SELECT id, nombre, precio, CONCAT('assets/images/productos/', imagen) as imagen 
               FROM productos 
               WHERE cantidad > 0 AND imagen IS NOT NULL 
               ORDER BY RAND() LIMIT " . $limite;
        return $this->selectAll($sql);
    }

    public function getCarouselData()
    {
        $data = [];
        
        // Obtener productos destacados
        $sql_destacados = "SELECT p.*, c.categoria FROM productos p 
                          INNER JOIN categorias c ON p.id_categoria = c.id 
                          WHERE p.estado = 1 ORDER BY p.id DESC LIMIT 8";
        $data['destacados'] = $this->selectAll($sql_destacados);
        
        // Obtener productos aleatorios
        $sql_aleatorios = "SELECT p.*, c.categoria FROM productos p 
                           INNER JOIN categorias c ON p.id_categoria = c.id 
                           WHERE p.estado = 1 ORDER BY RAND() LIMIT 8";
        $data['aleatorios'] = $this->selectAll($sql_aleatorios);
        
        // Obtener productos mejor valorados
        $sql_valorados = "SELECT p.*, c.categoria FROM productos p 
                         INNER JOIN categorias c ON p.id_categoria = c.id 
                         WHERE p.estado = 1 ORDER BY p.calificacion DESC LIMIT 8";
        $data['mejor_valorados'] = $this->selectAll($sql_valorados);
        
        // Obtener productos nuevos
        $sql_nuevos = "SELECT p.*, c.categoria FROM productos p 
                       INNER JOIN categorias c ON p.id_categoria = c.id 
                       WHERE p.estado = 1 ORDER BY p.fecha_registro DESC LIMIT 8";
        $data['nuevos'] = $this->selectAll($sql_nuevos);
        
        // Obtener ofertas
        $sql_ofertas = "SELECT p.*, c.categoria FROM productos p 
                        INNER JOIN categorias c ON p.id_categoria = c.id 
                        WHERE p.estado = 1 AND p.precio_oferta > 0 LIMIT 8";
        $data['ofertas'] = $this->selectAll($sql_ofertas);
        
        return $data;
    }

    public function formatearProducto($producto)
    {
        // Asegurarse de que la calificación siempre esté definida
        if (!isset($producto['calificacion'])) {
            $productos = new ProductosModel();
            $producto['calificacion'] = $productos->getPromedioReview($producto['id']);
        }
        
        // Redondear la calificación a un decimal
        $producto['calificacion'] = round(floatval($producto['calificacion']), 1);
        
        // Obtener colores y tallas
        $productos = new ProductosModel();
        $producto['colores'] = $productos->getColores($producto['id']);
        $producto['tallas'] = $productos->getTallas($producto['id']);
        
        return $producto;
    }
}
 
?>