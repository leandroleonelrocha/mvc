<?php


namespace DaVinci\Models;


class Caracteristica extends Modelo
{
    protected $table = 'caracteristicas';
    protected $primaryKey = 'id_caracteristica';
    protected $attributes = ['id_caracteristica', 'nombre', 'id_producto', 'valor'];

    protected $id_caracteristica;
    protected $nombre;
    protected $id_producto;
    protected $valor;

    /**
     * @return mixed
     */
    public function getIdCaracteristica()
    {
        return $this->id_caracteristica;
    }

    /**
     * @param mixed $id_caracteristica
     */
    public function setIdCaracteristica($id_caracteristica)
    {
        $this->id_caracteristica = $id_caracteristica;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getIdProducto()
    {
        return $this->id_producto;
    }

    /**
     * @param mixed $id_producto
     */
    public function setIdProducto($id_producto)
    {
        $this->id_producto = $id_producto;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }
}