<?php
class BaseModel extends Query {
    protected $tabla;
    
    public function __construct($tabla) {
        parent::__construct();
        $this->tabla = $tabla;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = ?";
        return $this->select($sql, [$id]);
    }

    public function getAll($estado = 1) {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = ?";
        return $this->selectAll($sql, [$estado]);
    }

    public function insertar($campos, $valores) {
        $columnas = implode(',', $campos);
        $parametros = implode(',', array_fill(0, count($campos), '?'));
        $sql = "INSERT INTO {$this->tabla} ($columnas) VALUES ($parametros)";
        return $this->insert($sql, $valores);
    }

    public function actualizar($campos, $valores, $id) {
        $set = implode('=?,', $campos) . '=?';
        $sql = "UPDATE {$this->tabla} SET $set WHERE id=?";
        $valores[] = $id;
        return $this->update($sql, $valores);
    }

    public function eliminar($id) {
        $sql = "UPDATE {$this->tabla} SET estado = ? WHERE id = ?";
        return $this->update($sql, [0, $id]);
    }
}
