<?php


namespace DaVinci\Models;


class Marca extends Modelo
{
    protected $table = "marcas";
    protected $primaryKey = 'id_marca';
    protected $attributes = ['id_marca', 'marca'];

    protected $id_marca;
    protected $marca;

    /**
     * @return mixed
     */
    public function getIdMarca()
    {
        return $this->id_marca;
    }

    /**
     * @param mixed $id_marca
     */
    public function setIdMarca($id_marca)
    {
        $this->id_marca = $id_marca;
    }

    /**
     * @return mixed
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param mixed $marca
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;
    }
}