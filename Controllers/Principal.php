<?php
class Principal extends Controller
{
    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function index()
    {
        // Inicializar ProductosModel para obtener productos destacados
        $productosModel = new ProductosModel();
        $productos = $productosModel->getProductosDestacados();

        // Verificar si hay productos y loguear el resultado
        if (empty($productos)) {
            error_log("No se encontraron productos destacados");
        } else {
            error_log("Productos destacados encontrados: " . count($productos));
        }

        // Preparar los datos para la vista
        $data['title'] = 'Productos Destacados';
        $data['productos'] = $productos;

        // Cargar la vista
        $this->views->getView('', "index", $data);
    }

    //obtener producto a partir de la lista de carrito
    public function listaProductos()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        $total = 0.00;
        if (!empty($json)) {
            foreach ($json as $producto) {
                $result = $this->model->getProducto($producto['idProducto']);
                $data['id'] = $result['id'];
                $data['nombre'] = $result['nombre'];
                $data['precio'] = $result['precio'];
                $data['cantidad'] = $producto['cantidad'];
                $data['imagen'] = $result['imagen'];
                $subTotal = $result['precio'] * $producto['cantidad'];
                $data['subTotal'] = number_format($subTotal, 2);
                array_push($array['productos'], $data);
                $total += $subTotal;
            }
        }        
        $array['total'] = number_format($total, 2);
        $array['totalPaypal'] = number_format($total, 2, '.', '');
        $array['moneda'] = MONEDA;
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function busqueda($valor)
    {
        $data = $this->model->getBusqueda($valor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function productosRecomendados()
    {
        $data = $this->model->getProductosAleatorios(4); // Obtener 4 productos aleatorios
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}