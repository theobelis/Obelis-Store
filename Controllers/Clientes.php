<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Clientes extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (empty($_SESSION['correoCliente'])) {
            echo '<div style="color:red;text-align:center;margin-top:2rem;">Error: Sesión de usuario no iniciada. Redirigiendo...</div>';
            header('Location: ' . BASE_URL);
            exit;
        }
        $data['perfil'] = 'si';
        $data['title'] = 'Tu Cuenta';
        $data['categorias'] = $this->model->getCategorias();
        $verificar = $this->model->getVerificar($_SESSION['correoCliente']);
        if (!$verificar || !is_array($verificar) || !isset($verificar['verify'])) {
            $verificar = ['verify' => 0];
        }
        $data['verificar'] = $verificar;
        $data['cliente'] = $this->model->getClienteById($_SESSION['idCliente']);
        $this->views->getView('principal', "index", $data);
    }
    public function registroDirecto()
    {
        if (isset($_POST['nombre']) && isset($_POST['clave'])) {
            if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['clave'])) {
                $mensaje = array('msg' => 'TODO LOS CAMPOS SON REQUERIDOS', 'icono' => 'warning');
            } else {
                $nombre = $_POST['nombre'];
                $correo = $_POST['correo'];
                $clave = $_POST['clave'];
                
                // Validar que nombre y apellido solo contengan letras y espacios
                if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
                    $mensaje = array('msg' => 'El nombre solo puede contener letras y espacios', 'icono' => 'warning');
                    echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
                    die();
                }
                
                // Validar el apellido si se proporcionó
                if (!empty($_POST['apellido']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $_POST['apellido'])) {
                    $mensaje = array('msg' => 'El apellido solo puede contener letras y espacios', 'icono' => 'warning');
                    echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
                    die();
                }
                $verificar = $this->model->getVerificar($correo);
                if (empty($verificar)) {
                    $token = md5($correo);
                    $hash = password_hash($clave, PASSWORD_DEFAULT);
                    $data = $this->model->registroDirecto($nombre, $correo, $hash, $token);
                    if ($data > 0) {
                        $_SESSION['idCliente'] = $data;
                        $_SESSION['correoCliente'] = $correo;
                        $_SESSION['nombreCliente'] = $nombre;
                        $mensaje = array('msg' => 'registrado con éxito', 'icono' => 'success', 'token' => $token);
                    } else {
                        $mensaje = array('msg' => 'error al registrarse', 'icono' => 'error');
                    }
                } else {
                    $mensaje = array('msg' => 'YA TIENES UNA CUENTA', 'icono' => 'warning');
                }
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function enviarCorreo()
    {
        if (isset($_POST['correo']) && isset($_POST['token'])) {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = HOST_SMTP;                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = USER_SMTP;                     //SMTP username
                $mail->Password   = PASS_SMTP;                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = PUERTO_SMTP;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('peppecanto18@gmail.com', TITLE);
                $mail->addAddress($_POST['correo']);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Verifica tu cuenta de ' . TITLE;
                $mail->Body    = 'Haz click en<a href="' . BASE_URL . 'clientes/verificarCorreo/' . $_POST['token'] . '">Verificar</a> para confirmar tu correo.';
                $mail->AltBody = 'GRACIAS POR LA PREFERENCIA';

                $mail->send();
                $mensaje = array('msg' => 'CORREO ENVIADO, REVISA TU BANDEJA DE ENTRADA - SPAN', 'icono' => 'success');
            } catch (Exception $e) {
                $mensaje = array('msg' => 'ERROR AL ENVIAR CORREO: ' . $mail->ErrorInfo, 'icono' => 'error');
            }
        } else {
            $mensaje = array('msg' => 'ERROR FATAL: ', 'icono' => 'error');
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function verificarCorreo($token)
    {
        $verificar = $this->model->getToken($token);
        if (!empty($verificar)) {
            $this->model->actualizarVerify($verificar['id']);
            header('Location: ' . BASE_URL . 'clientes');
        }
    }

    //login directo
    public function loginDirecto()
    {
        if (isset($_POST['correoLogin']) && isset($_POST['claveLogin'])) {
            if (empty($_POST['correoLogin']) || empty($_POST['claveLogin'])) {
                $mensaje = array('msg' => 'TODO LOS CAMPOS SON REQUERIDOS', 'icono' => 'warning');
            } else {
                $correo = $_POST['correoLogin'];
                $clave = $_POST['claveLogin'];
                $verificar = $this->model->getVerificar($correo);
                if (!empty($verificar)) {
                    if (password_verify($clave, $verificar['clave'])) {
                        $_SESSION['idCliente'] = $verificar['id'];
                        $_SESSION['correoCliente'] = $verificar['correo'];
                        $_SESSION['nombreCliente'] = $verificar['nombre'];
                        $mensaje = array('msg' => 'OK', 'icono' => 'success');
                    } else {
                        $mensaje = array('msg' => 'CONTRASEÑA INCORRECTA', 'icono' => 'error');
                    }
                } else {
                    $mensaje = array('msg' => 'EL CORREO NO EXISTE', 'icono' => 'warning');
                }
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    //registrar pedidos
    public function registrarPedido()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $pedidos = $json['pedidos'];
        $productos = $json['productos'];
        if (is_array($pedidos) && is_array($productos)) {
            $id_transaccion = $pedidos['id'];
            $monto = $pedidos['purchase_units'][0]['amount']['value'];
            $estado = $pedidos['status'];
            $fecha = date('Y-m-d H:i:s');
            $email = $pedidos['payer']['email_address'];
            $nombre = $pedidos['payer']['name']['given_name'];
            $apellido = $pedidos['payer']['name']['surname'];
            $direccion = $pedidos['purchase_units'][0]['shipping']['address']['address_line_1'];
            $ciudad = $pedidos['purchase_units'][0]['shipping']['address']['admin_area_2'];
            $id_cliente = $_SESSION['idCliente'];
            $data = $this->model->registrarPedido(
                $id_transaccion,
                $monto,
                $estado,
                $fecha,
                $email,
                $nombre,
                $apellido,
                $direccion,
                $ciudad,
                $id_cliente
            );
            if ($data > 0) {
                foreach ($productos as $producto) {
                    $temp = $this->model->getProducto($producto['idProducto']);
                    $color = isset($producto['color']) ? $producto['color'] : null;
                    $talla = isset($producto['talla']) ? $producto['talla'] : null;
                    $this->model->registrarDetalle($temp['nombre'], $temp['precio'], $producto['cantidad'], $data, $producto['idProducto'], $color, $talla);
                }
                $mensaje = array('msg' => 'pedido registrado', 'icono' => 'success');
            } else {
                $mensaje = array('msg' => 'error al registrar el pedido', 'icono' => 'error');
            }
        } else {
            $mensaje = array('msg' => 'error fatal con los datos', 'icono' => 'error');
        }
        echo json_encode($mensaje);
        die();
    }
    //listar productos pendientes
    public function listarPendientes()
    {
        $id_cliente = $_SESSION['idCliente'];
        $data = $this->model->getPedidos($id_cliente);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['accion'] = '<div class="text-center"><button class="btn btn-primary" type="button" onclick="verPedido(' . $data[$i]['id'] . ')"><i class="fas fa-eye"></i></button></div>';
        }
        echo json_encode($data);
        die();
    }
    public function verPedido($idPedido)
    {
        $data['pedido'] = $this->model->getPedido($idPedido);
        $data['productos'] = $this->model->verPedidos($idPedido);
        $data['moneda'] = MONEDA;
        echo json_encode($data);
        die();
    }

    public function salir()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
    }
    // Actualizar perfil de cliente (AJAX)
    public function actualizarPerfil()
    {
        header('Content-Type: application/json');
        
        if (empty($_SESSION['idCliente'])) {
            echo json_encode([
                'icono' => 'error',
                'msg' => 'Sesión no válida'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'icono' => 'error',
                'msg' => 'Método no permitido'
            ]);
            return;
        }

        try {
            $id = $_SESSION['idCliente'];
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $clave = !empty($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Verificar la contraseña actual
            $cliente = $this->model->getClienteById($id);
            
            if (!$cliente) {
                error_log("Error en actualizarPerfil: No se encontró el cliente con ID $id");
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'Error al verificar los datos del usuario'
                ]);
                return;
            }

            // Validar que la contraseña coincida
            if (!password_verify($password_confirm, $cliente['clave'])) {
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'La contraseña actual es incorrecta'
                ]);
                return;
            }

            if (empty($nombre) || empty($correo)) {
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'El nombre y correo son obligatorios'
                ]);
                return;
            }

            // Validar que nombre y apellido solo contengan letras y espacios
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'El nombre solo puede contener letras y espacios'
                ]);
                return;
            }

            if (!empty($apellido) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellido)) {
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'El apellido solo puede contener letras y espacios'
                ]);
                return;
            }

            // Verificar si el correo existe para otro usuario
            if ($this->model->correoExisteOtro($correo, $id)) {
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'El correo ya está registrado con otro usuario'
                ]);
                return;
            }

            // Procesar la foto si se subió una nueva
            $foto_nombre = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = $_FILES['foto'];
                $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
                
                if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    echo json_encode([
                        'icono' => 'error',
                        'msg' => 'El formato de imagen no es válido'
                    ]);
                    return;
                }

                if ($foto['size'] > 2 * 1024 * 1024) {
                    echo json_encode([
                        'icono' => 'error',
                        'msg' => 'La imagen no debe superar 2MB'
                    ]);
                    return;
                }

                $foto_nombre = uniqid() . '.' . $extension;
                $dir = "assets/images/fotos_clientes/$id";
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                if (!move_uploaded_file($foto['tmp_name'], "$dir/$foto_nombre")) {
                    echo json_encode([
                        'icono' => 'error',
                        'msg' => 'Error al guardar la imagen'
                    ]);
                    return;
                }

                // Actualizar la foto en la base de datos
                $this->model->actualizarFoto($id, $foto_nombre);
            }

            // Actualizar el perfil
            $actualizado = $this->model->actualizarPerfil($id, $nombre, $apellido, $correo, $clave);
            
            if ($actualizado) {
                $_SESSION['nombreCliente'] = $nombre;
                $_SESSION['correoCliente'] = $correo;
                
                $response = [
                    'icono' => 'success',
                    'msg' => 'Perfil actualizado correctamente'
                ];

                if ($foto_nombre) {
                    $response['foto'] = BASE_URL . "assets/images/fotos_clientes/$id/$foto_nombre";
                }

                echo json_encode($response);
            } else {
                echo json_encode([
                    'icono' => 'error',
                    'msg' => 'Error al actualizar el perfil'
                ]);
            }

        } catch (Exception $e) {
            error_log("Error en actualizarPerfil: " . $e->getMessage());
            echo json_encode([
                'icono' => 'error',
                'msg' => 'Error del servidor al actualizar el perfil'
            ]);
        }
    }
}
