<?php
class Productos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['nombre_usuario'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {
        $data['title'] = 'productos';
        $data['categorias'] = $this->model->getCategorias();
        $this->views->getView('admin/productos', "index", $data);
    }
    public function listar()
    {
        $data = $this->model->getProductos(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . $data[$i]['imagen'] . '" alt="' . $data[$i]['nombre'] . '" width="50">';
            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-success" type="button" onclick="agregarImagenes(' . $data[$i]['id'] . ')"><i class="fas icon-images"></i></button>
            <button class="btn btn-primary" type="button" onclick="editPro(' . $data[$i]['id'] . ')"><i class="fas icon-pencil"></i></button>
            <button class="btn btn-danger" type="button" onclick="eliminarPro(' . $data[$i]['id'] . ')"><i class="fas icon-bin"></i></button>
        </div>';
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        if (isset($_POST['nombre']) && isset($_POST['precio'])) {
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $descripcion = $_POST['descripcion'];
            $categoria = $_POST['categoria'];
            $destacado = isset($_POST['destacado']) ? intval($_POST['destacado']) : 0;
            // Manejo robusto de colores y tallas (acepta array o string CSV)
            $colores = isset($_POST['colores']) ? $_POST['colores'] : [];
            if (is_string($colores)) {
                $colores = array_filter(array_map('trim', explode(',', $colores)));
            }
            if (!is_array($colores)) $colores = [];
            $tallas = isset($_POST['tallas']) ? $_POST['tallas'] : [];
            if (is_string($tallas)) {
                $tallas = array_filter(array_map('trim', explode(',', $tallas)));
            }
            if (!is_array($tallas)) $tallas = [];
            $calificacion = isset($_POST['calificacion']) && $_POST['calificacion'] !== '' ? floatval($_POST['calificacion']) : null;
            $imagen = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;
            $tmp_name = $imagen ? $imagen['tmp_name'] : null;
            $id = $_POST['id'];
            $ruta = 'assets/images/productos/';
            $nombreImg = date('YmdHis');
            if (empty($nombre) || empty($precio) || empty($cantidad)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                if (empty($id)) {
                    // NUEVO PRODUCTO
                    $destino = (!empty($imagen) && !empty($imagen['name'])) ? $ruta . $nombreImg . '.jpg' : $ruta . 'default.png';
                    $data = $this->model->registrar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $destacado);
                    if ($data > 0) {
                        if (!empty($imagen) && !empty($imagen['name'])) {
                            move_uploaded_file($tmp_name, $destino);
                        }
                        // Guardar colores, tallas y calificación
                        $id_producto = $data;
                        $this->model->guardarColores($id_producto, $colores);
                        $this->model->guardarTallas($id_producto, $tallas);
                        if (!is_null($calificacion)) {
                            $this->model->guardarCalificacion($id_producto, $calificacion);
                        }
                        $respuesta = array('msg' => 'producto registrado', 'icono' => 'success', 'idProducto' => $id_producto);
                    } else {
                        $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                    }
                } else {
                    // MODIFICAR PRODUCTO
                    $destino = (!empty($imagen) && !empty($imagen['name'])) ? $ruta . $nombreImg . '.jpg' : (!empty($_POST['imagen_actual']) ? $_POST['imagen_actual'] : $ruta . 'default.png');
                    $data = $this->model->modificar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $destacado, $id);
                    if ($data == 1) {
                        if (!empty($imagen) && !empty($imagen['name'])) {
                            move_uploaded_file($tmp_name, $destino);
                        }
                        $this->model->guardarColores($id, $colores);
                        $this->model->guardarTallas($id, $tallas);
                        if (!is_null($calificacion)) {
                            $this->model->guardarCalificacion($id, $calificacion);
                        }
                        $respuesta = array('msg' => 'producto modificado', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'error al modificar', 'icono' => 'error');
                    }
                }
            }
            echo json_encode($respuesta);
        }
        die();
    }
    //eliminar pro
    public function delete($idPro)
    {
        if (is_numeric($idPro)) {
            $data = $this->model->eliminar($idPro);
            if ($data == 1) {
                $respuesta = array('msg' => 'producto dado de baja', 'icono' => 'success');
            } else {
                $respuesta = array('msg' => 'error al eliminar', 'icono' => 'error');
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta);
        die();
    }
    //editar pro
    public function edit($idPro)
    {
        if (is_numeric($idPro)) {
            $data = $this->model->getProducto($idPro);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    private function checkImageRequirements() {
        $required_extensions = ['gd', 'fileinfo'];
        $missing = [];
        
        foreach ($required_extensions as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }
        
        if (!empty($missing)) {
            die(json_encode([
                'error' => 'Extensiones PHP requeridas no están instaladas: ' . implode(', ', $missing),
                'icono' => 'error'
            ]));
        }
        
        return true;
    }

    public function galeriaImagenes()
    {
        $this->checkImageRequirements();
        
        $id = $_POST['idProducto'];
        $folder_name = 'assets/images/productos/' . $id . '/';
        
        if (!empty($_FILES)) {
            if (!file_exists($folder_name)) {
                mkdir($folder_name, 0777, true);
            }

            // Obtener la siguiente secuencia de número para la imagen
            $existingFiles = scandir($folder_name);
            $maxNumber = 0;
            foreach ($existingFiles as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'webp') {
                    $fileName = pathinfo($file, PATHINFO_FILENAME);
                    if (is_numeric($fileName) && $fileName > $maxNumber) {
                        $maxNumber = (int)$fileName;
                    }
                }
            }
            $nextNumber = $maxNumber + 1;

            $temp_name = $_FILES['file']['tmp_name'];
            
            // Convertir imagen a WebP
            $image = null;
            $mime = mime_content_type($temp_name);
            
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($temp_name);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($temp_name);
                    // Mantener transparencia para PNG
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                default:
                    die(json_encode([
                        'error' => 'Formato de imagen no soportado. Solo se permiten JPG y PNG.',
                        'icono' => 'error'
                    ]));
            }

            if ($image) {
                // Guardar como WebP
                $newFileName = $nextNumber . '.webp';
                $destination = $folder_name . $newFileName;
                
                // Calidad WebP: 80 (0-100, donde 100 es la mejor calidad)
                $success = imagewebp($image, $destination, 80);
                imagedestroy($image);
                
                if (!$success) {
                    die(json_encode([
                        'error' => 'Error al guardar la imagen en formato WebP',
                        'icono' => 'error'
                    ]));
                }
            }
        }
    }

    public function verGaleria($id_producto)
    {
        $result = array();
        $directorio = 'assets/images/productos/' . $id_producto;
        if (file_exists($directorio)) {
            $imagenes = scandir($directorio);
            if ($imagenes !== false) {
                foreach ($imagenes as $file) {
                    if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'webp') {
                        array_push($result, $file);
                    }
                }
                // Ordenar numéricamente por nombre de archivo
                natsort($result);
            }
        }
        echo json_encode($result);
        die();
    }

    public function eliminarImg()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $destino = 'assets/images/productos/' . $json['url'];
        if (unlink($destino)) {
            $res = array('msg' => 'IMAGEN ELIMINADO', 'icono' => 'success');
        }else{
            $res = array('msg' => 'ERROR AL ELIMINAR', 'icono' => 'error');
        }
        echo json_encode($res);
        die();
    }
    // Endpoint para obtener atributos de producto (colores, tallas, calificación)
    public function atributos($idPro)
    {
        if (is_numeric($idPro)) {
            $colores = $this->model->getColores($idPro);
            $tallas = $this->model->getTallas($idPro);
            $calificacion = $this->model->getCalificaciones($idPro);
            echo json_encode([
                'colores' => $colores,
                'tallas' => $tallas,
                'calificacion' => $calificacion
            ], JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    // Obtener reviews de un producto (GET)
    public function reviews($idPro)
    {
        if (is_numeric($idPro)) {
            $reviews = $this->model->getReviews($idPro);
            $promedio = $this->model->getPromedioReview($idPro);
            echo json_encode([
                'reviews' => $reviews,
                'promedio' => $promedio
            ], JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // Registrar review de producto (POST, requiere usuario logueado)
    public function registrarReview()
    {
        if (!isset($_SESSION['idCliente'])) {
            echo json_encode(['msg' => 'Debes iniciar sesión para dejar un review', 'icono' => 'warning']);
            die();
        }
        $datos = json_decode(file_get_contents('php://input'), true);
        $id_producto = $datos['id_producto'] ?? null;
        $calificacion = $datos['calificacion'] ?? null;
        $comentario = trim($datos['comentario'] ?? '');
        $id_cliente = $_SESSION['idCliente'];
        $nombre_cliente = $_SESSION['nombreCliente'] ?? '';
        if (!$id_producto || !$calificacion || $comentario === '') {
            echo json_encode(['msg' => 'Todos los campos son obligatorios', 'icono' => 'warning']);
            die();
        }
        // Validar que el usuario no haya dejado review antes (opcional)
        $yaReview = $this->model->yaReview($id_producto, $id_cliente);
        if ($yaReview) {
            echo json_encode(['msg' => 'Ya has dejado un review para este producto', 'icono' => 'info']);
            die();
        }
        $res = $this->model->guardarReview($id_producto, $id_cliente, $nombre_cliente, $calificacion, $comentario);
        if ($res) {
            echo json_encode(['msg' => '¡Gracias por tu review!', 'icono' => 'success']);
        } else {
            echo json_encode(['msg' => 'Error al guardar review', 'icono' => 'error']);
        }
        die();
    }

    public function detalle($id)
    {
        $data = $this->model->getProducto($id);
        if (empty($data)) {
            header("location: " . BASE_URL);
            return;
        }
        // Obtener galería de imágenes
        $galeria = $this->model->getGaleria($id);
        if (!empty($galeria)) {
            $data['galeria'] = array_map(function($img) use ($id) {
                return BASE_URL . 'assets/images/productos/' . $id . '/' . $img;
            }, $galeria);
        }
        // Obtener colores
        $colores = $this->model->getColores($id);
        if (!empty($colores)) {
            $data['colores'] = array_column($colores, 'color');
        }
        // Obtener tallas
        $tallas = $this->model->getTallas($id);
        if (!empty($tallas)) {
            $data['tallas'] = array_column($tallas, 'talla');
        }
        // Obtener calificación
        $calificacion = $this->model->getCalificaciones($id);
        if (!empty($calificacion)) {
            $data['calificacion'] = $calificacion[0]['calificacion'];
        }
        // Obtener reviews
        $reviews = $this->model->getReviews($id);
        if (!empty($reviews)) {
            $data['reviews'] = $reviews;
        }
        
        $this->views->getView('productos', "detalle", $data);
    }

    // Guardar el nuevo orden de la galería de imágenes (drag & drop)
    public function ordenarGaleria()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idProducto = $datos['idProducto'] ?? null;
        $orden = $datos['orden'] ?? [];
        $directorio = 'assets/images/productos/' . $idProducto . '/';
        if (!$idProducto || !is_array($orden) || !file_exists($directorio)) {
            echo json_encode(['msg' => 'Datos inválidos', 'icono' => 'error']);
            die();
        }
        // Renombrar archivos según el nuevo orden
        $i = 1;
        foreach ($orden as $nombreActual) {
            $rutaActual = $directorio . $nombreActual;
            $nuevoNombre = $i . '.webp';
            $rutaNueva = $directorio . $nuevoNombre;
            if ($nombreActual !== $nuevoNombre && file_exists($rutaActual)) {
                // Si el nombre ya existe, renombrar temporalmente para evitar colisión
                if (file_exists($rutaNueva)) {
                    rename($rutaNueva, $directorio . 'temp_' . $nuevoNombre);
                }
                rename($rutaActual, $rutaNueva);
            }
            $i++;
        }
        // Limpiar temporales
        foreach (scandir($directorio) as $file) {
            if (strpos($file, 'temp_') === 0) {
                $nuevo = substr($file, 5);
                rename($directorio . $file, $directorio . $nuevo);
            }
        }
        echo json_encode(['msg' => 'Orden de imágenes actualizado', 'icono' => 'success']);
        die();
    }
}
