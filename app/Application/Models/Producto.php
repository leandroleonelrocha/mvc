<?php
namespace Application\Models;

use Application\DB\Connection;
use PDO;

class Producto extends Modelo
{
    protected $table = "productos";
    protected $primaryKey = "id_producto";
    protected $attributes = ['id_producto', 'nombre', 'precio', 'id_marca', 'id_categoria', 'descripcion', 'stock', 'cuotas_sin_interes', 'imagen', 'slug'];
    protected $jsonExclude = ['stock', 'cuotas_sin_interes'];
    protected $id_producto;
    protected $nombre;
    protected $precio;
    protected $id_marca;
    protected $id_categoria;
    protected $descripcion;
    protected $stock;
    protected $cuotas_sin_interes;
    protected $imagen;
    protected $slug;

    /** @var Marca */
    protected $marca;

    /** @var Caracteristica[] */
    protected $caracteristicas = [];

    /**
     * Retorna un array con todos los items de la tabla.
     *
     * @return array|static
     */
    public function getAll()
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM productos p
                inner join marcas m
                on p.id_marca = m.id_marca
                order by p.id_producto";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Creamos el producto.
            $obj = new Producto;
            $obj->loadDataFromArray($fila);
            // Dentro de los datos del producto, tenemos también los datos
            // de la marca, así que podemos crear la marca con esos datos.
            $marca = new Marca;
            $marca->loadDataFromArray($fila);
            // Asociamos la marca al producto.
            $obj->setMarca($marca);
            // Agregamos el producto al array.
            $salida[$obj->getIdProducto()] = $obj;
        }

        // Obtenemos todos los ids de los productos.
        // Esto lo vamos a usar para asegurarnos de que solo se traigan
        // las características de los productos que estamos mostrando.
        // Muy útil especialmente si estamos paginando los resultados.
        $ids = array_keys($salida);

        // Generamos los holders para la consulta de las características.
        // Básicamente, necesitamos tener tantos "?" como ids en el array.
        // array_fill(0, count($ids), '?')
        // Crea un nuevo array que empiece en el índice 0, cree tantos
        // valores como la cantidad de items de $ids, y que todos sean
        // '?'.
        // En pocas palabras, le pedimos que cree un array con la misma
        // cantidad de elementos que $ids, pero todos los valores '?'.
        // Por último, los unimos en un string usando implode, pegándolos
        // con una ','.
        $holders = implode(', ', array_fill(0, count($ids), '?'));

        /*************************
         Características
         *************************/
        // Empezamos por traer todas las características con sus productos.
        $queryCar = "select * from productos_tienen_caracteristicas pc
                    inner join caracteristicas c
                    on pc.id_caracteristica = c.id_caracteristica
                    where pc.id_producto in ($holders)";
        $stmt = $db->prepare($queryCar);
        // Le pasamos el array de $ids para los valores del holder.
        $stmt->execute($ids);

        // Recorremos las *combinaciones* de características y valores de
        // cada producto.
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $carac = new Caracteristica;
            $carac->loadDataFromArray($fila);
            // Asociamos esta característica al producto que corresponde.
            // Como los productos los guardamos en el array de $salida
            // usando como key el id del producto, lo podemos buscar
            // fácilmente por dicho id.
            $producto = $salida[$carac->getIdProducto()];
            $producto->addCaracteristicas($carac);
        }

        return $salida;
    }

    /**
     * Busca el registro por su $pk.
     *
     * @param mixed $pk
     */
    public function getByPk($pk)
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM productos p
                inner join marcas m
                on p.id_marca = m.id_marca
                WHERE p.id_producto = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->loadDataFromArray($fila);
        // Dentro de los datos del producto, tenemos también los datos
        // de la marca, así que podemos crear la marca con esos datos.
        $marca = new Marca;
        $marca->loadDataFromArray($fila);
        // Asociamos la marca al producto.
        $this->setMarca($marca);
    }

    /**
     * Carga los datos del producto buscando por el slug.
     *
     * @param $slug
     */
    public function getBySlug($slug)
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM productos p
                inner join marcas m
                on p.id_marca = m.id_marca
                WHERE p.slug = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$slug]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->loadDataFromArray($fila);
        // Dentro de los datos del producto, tenemos también los datos
        // de la marca, así que podemos crear la marca con esos datos.
        $marca = new Marca;
        $marca->loadDataFromArray($fila);
        // Asociamos la marca al producto.
        $this->setMarca($marca);
    }

    /**
     * Graba las características a este producto.
     * El array $data debe tener como valores los pares de valores para
     * cada característica (id, valor). Por ejemplo:
     * [
     *  ['id_caracteristica' => 1, 'valor' => '55"'],
     *  ['id_caracteristica' => 2, 'valor' => 'LED'],
     *  ['id_caracteristica' => 5, 'valor' => 'Sí'],
     * ]
     *
     * @param array $data
     * @return bool
     */
    public function grabarCaracteristicas($data)
    {
        // Obtenemos la conexión.
        $db = Connection::getConnection();

        // Armamos el listado de valores para la consulta de insert.
        $queryValues = [];
        $valores = [];
        foreach($data as $item) {
            $queryValues[] = '(?, ?, ?)';
            $valores[] = $this->getIdProducto();
            $valores[] = $item['id_caracteristica'];
            $valores[] = $item['valor'];
        }

        $query = "INSERT INTO productos_tienen_caracteristicas (id_producto, id_caracteristica, valor)
                VALUES " . implode(', ', $queryValues);

        $stmt = $db->prepare($query);
        $exito = $stmt->execute($valores);

        // TODO: Cargar las caracteristicas generadas como parte del
        // objeto Producto.

        return $exito;
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
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param mixed $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

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
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * @param mixed $id_categoria
     */
    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return mixed
     */
    public function getCuotasSinInteres()
    {
        return $this->cuotas_sin_interes;
    }

    /**
     * @param mixed $cuotas_sin_interes
     */
    public function setCuotasSinInteres($cuotas_sin_interes)
    {
        $this->cuotas_sin_interes = $cuotas_sin_interes;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Marca
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param Marca $marca
     */
    public function setMarca(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * @return Caracteristica[]
     */
    public function getCaracteristicas(): array
    {
        return $this->caracteristicas;
    }

    /**
     * @param Caracteristica $caracteristicas
     */
    public function addCaracteristicas(Caracteristica $caracteristicas)
    {
        $this->caracteristicas[] = $caracteristicas;
    }
}