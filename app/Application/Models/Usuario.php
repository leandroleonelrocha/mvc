<?php
namespace Application\Models;

// Agregamos que la clase Usuario implemente la interfaz
// Autenticable.
use Application\Auth\Contracts\Autenticable;
use Application\DB\Connection;
use PDO;

class Usuario implements Autenticable
{
    // Propiedades
    protected $id;
    protected $usuario;
    protected $password;
    protected $email;
    
    /**
     * Busca un usuario por su nombre de usuario.
     * Los datos del usuario se cargan en la propia
     * instancia.
     *
     * El ": bool" del final indica que el método debe
     * retornar un valor de ese tipo de dato.
     *
     * @param string $usuario
     * @return bool
     */
    public function buscarPorUsuario($usuario) : bool
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM usuarios
                WHERE usuario = ?";
        // Preparamos la consulta, y la ejecutamos.
        $stmt = $db->prepare($query);
        $stmt->execute([$usuario]);
        
        // Obtenemos los datos, y los cargamos.
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificamos si había un usuario.
        if($fila) {
            $this->cargarDatosDeArray($fila);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Carga los datos del array $fila en el objeto.
     *
     * @param array $fila
     */
    public function cargarDatosDeArray($fila)
    {
        $this->setId($fila['id']);
        $this->setUsuario($fila['usuario']);
        $this->setPassword($fila['password']);
        $this->setEmail($fila['email']);
    }
    
    public function crear()
    {
        
    }
    
    public function editar()
    {
        
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}