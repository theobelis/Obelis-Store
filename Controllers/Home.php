<?php
class Home extends Controller
{
    public function __construct() {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        $data['perfil'] = 'no';
        $data['title'] = 'Pagina Principal';
        
        // Instanciar ProductosModel para obtener productos destacados
        $productosModel = new ProductosModel();
        $data['productos'] = $productosModel->getProductosDestacados();
        
        // Obtener categorías y productos por categoría
        $data['categorias'] = $this->model->getCategorias();        
        for ($i=0; $i < count($data['categorias']); $i++) {
            $data['categorias'][$i]['productos'] = $this->model->getProductos($data['categorias'][$i]['id']);
        }
        $this->views->getView('home', "index", $data);
    }
}