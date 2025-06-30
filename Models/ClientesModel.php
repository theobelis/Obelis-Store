<?php
class ClientesModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getCategorias() {
        $sql = "SELECT * FROM categorias WHERE estado = 1";
        return $this->selectAll($sql);
    }
    public function registroDirecto($nombre, $correo, $clave, $token)
    {
        $sql = "INSERT INTO clientes (nombre, correo, clave, token) VALUES (?,?,?,?)";
        $datos = array($nombre, $correo, $clave, $token);
        $data = $this->insertar($sql, $datos);
        if ($data > 0) {
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function getToken($token)
    {
        $sql = "SELECT * FROM clientes WHERE token = ?";
        return $this->select($sql, [$token]);
    }
    public function actualizarVerify($id)
    {
        $sql = "UPDATE clientes SET token=?, verify=? WHERE id=?";
        $datos = array(null, 1, $id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function getVerificar($correo)
    {
        $sql = "SELECT * FROM clientes WHERE correo = ?";
        return $this->select($sql, [$correo]);
    }

    public function registrarPedido($id_transaccion, $monto, $estado, $fecha, $email,
    $nombre, $apellido, $direccion, $ciudad, $id_cliente)
    {
        $sql = "INSERT INTO pedidos (id_transaccion, monto, estado, fecha, email,
        nombre, apellido, direccion, ciudad, id_cliente) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $datos = array($id_transaccion, $monto, $estado, $fecha, $email,
        $nombre, $apellido, $direccion, $ciudad, $id_cliente);
        $data = $this->insertar($sql, $datos);
        if ($data > 0) {
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function getProducto($id_producto)
    {
        $sql = "SELECT * FROM productos WHERE id = ?";
        return $this->select($sql, [$id_producto]);
    }
    public function registrarDetalle($producto, $precio, $cantidad, $id_pedido, $id_producto, $color = null, $talla = null)
    {
        $sql = "INSERT INTO detalle_pedidos (producto, precio, cantidad, id_pedido, id_producto, color, talla) VALUES (?,?,?,?,?,?,?)";
        $datos = array($producto, $precio, $cantidad, $id_pedido, $id_producto, $color, $talla);
        $data = $this->insertar($sql, $datos);
        if ($data > 0) {
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function getPedidos($id_cliente)
    {
        $sql = "SELECT * FROM pedidos WHERE id_cliente = ?";
        return $this->selectAll($sql, [$id_cliente]);
    }
    public function getPedido($idPedido)
    {
        $sql = "SELECT * FROM pedidos WHERE id = ?";
        return $this->select($sql, [$idPedido]);
    }
    public function verPedidos($idPedido)
    {
        $sql = "SELECT d.* FROM pedidos p INNER JOIN detalle_pedidos d ON p.id = d.id_pedido WHERE p.id = ?";
        return $this->selectAll($sql, [$idPedido]);
    }
    // Verificar si el correo ya existe para otro cliente
    public function correoExisteOtro($correo, $idCliente)
    {
        $sql = "SELECT id FROM clientes WHERE correo = ? AND id != ?";
        $res = $this->select($sql, [$correo, $idCliente]);
        return !empty($res);
    }
    // Actualizar perfil del cliente
    public function actualizarPerfil($id, $nombre, $apellido, $correo, $hash)
    {
        try {
            if (!empty($hash)) {
                $sql = "UPDATE clientes SET nombre=?, apellido=?, correo=?, clave=? WHERE id=?";
                $params = [$nombre, $apellido, $correo, $hash, $id];
            } else {
                $sql = "UPDATE clientes SET nombre=?, apellido=?, correo=? WHERE id=?";
                $params = [$nombre, $apellido, $correo, $id];
            }
            $result = $this->save($sql, $params);
            if ($result === false) {
                error_log("Error en actualizarPerfil: La consulta falló");
                return false;
            }
            return true;
        } catch (Exception $e) {
            error_log("Error en actualizarPerfil: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarFoto($id, $foto)
    {
        try {
            $sql = "UPDATE clientes SET foto = ? WHERE id = ?";
            return $this->save($sql, [$foto, $id]);
        } catch (Exception $e) {
            error_log("Error en actualizarFoto: " . $e->getMessage());
            return false;
        }
    }

    public function getClienteById($id)
    {
        try {
            $sql = "SELECT id, nombre, apellido, correo, foto, verify, clave FROM clientes WHERE id = ?";
            $result = $this->select($sql, [$id]);
            if ($result === false) {
                error_log("Error en getClienteById: No se encontró el cliente con ID $id");
                return null;
            }
            return $result;
        } catch (Exception $e) {
            error_log("Error en getClienteById: " . $e->getMessage());
            return null;
        }
    }

    public function verificarCorreo($correo)
    {
        $sql = "SELECT id FROM clientes WHERE correo = '$correo'";
        $res = $this->select($sql);
        return !empty($res);
    }
}
 
?>