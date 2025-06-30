<?php
class Controller {
    protected $views;
    protected $model;
    protected $loadedModel = false;

    public function __construct() {
        $this->views = new Views();
        $this->loadModel();
    }

    protected function loadModel() {
        if ($this->loadedModel) {
            return;
        }

        $modelName = get_class($this) . "Model";
        $modelPath = "Models/" . $modelName . ".php";
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            $this->model = new $modelName();
            $this->loadedModel = true;
        }
    }

    protected function jsonResponse($data, $code = 200) {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    protected function validateRequest($method = 'POST') {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Método no permitido'
            ], 405);
        }
    }

    protected function isAuthenticated() {
        if (empty($_SESSION['idCliente'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'No autorizado'
            ], 401);
        }
        return true;
    }
}
 
?>