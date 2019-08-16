<?php
namespace DaVinci\Auth;

/*
Esta clase maneja la autenticación, y solo la 
autenticación.
Conoce de la clase Usuario, ya que es la que usa para
tratar de loguear, pero NO sabe nada de cómo trabaja
por dentro (ej: desconoce la tabla usuarios).
*/
use DaVinci\Auth\Contracts\Autenticable;
use DaVinci\Storage\Session;

class Auth
{
    protected $user;
    
    /**
     * Instancia la clase para el usuario.
     * Al pedir una interfaz como parámetro, le exigimos
     * a php que controle que lo que me pasen sea una
     * clase que *implemente* esa interfaz.
     *
     * @param Autenticable $user
     */
    public function __construct(Autenticable $user)
    {
        // En vez de tener hard-codeada la dependencia
        // con la clase Usuario, podemos empezar a
        // moverla para que nos proporcionen el usuario.
        $this->user = $user;
    }
    
    /**
     * Intenta loguear al usuario.
     *
     * @param string $usuario
     * @param string $password
     * @return int 0 si el login es exitoso. 1 si el password es incorrecto. 2 si el usuario es incorrecto.
     */
    public function login($usuario, $password)
    {
        // Buscamos al usuario por el $usuario.
        // En vez de usar a Usuario, pidiera una clase
        // que implemente la interfaz Autenticable.
        
        if($this->user->buscarPorUsuario($usuario)) {
            // Tenemos al usuario, ahora debemos 
            // comparar los passwords.
            if(password_verify($password, $this->user->getPassword())) {
                // El usuario y el pass son válidos!
                // Logueamos al usuario.
                Session::set('id', $this->user->getId());
                Session::set('usuario', $this->user->getUsuario());
                // Técnicamente, se puede guardar objetos
                // en una variable de sesión. No lo vamos
                // a hacer acá, pero se puede.
                // $_SESSION['user'] = $user;

                // En CWM, acá podríamos crear la cookie.

                return 0;
            }
            return 1;
        }
        return 2;
    }
    
    /**
     * Cierra la sesión.
     */
    public function logout()
    {
        // El session_destroy va a cerrar la sesión,
        // pero va a borrar todos los datos de la sesión,
        // incluidos los que no tengan que ver con la
        // autenticación.
        // session_destroy();
        // Una mejor opción es especificar que se
        // limpien los datos que setea esta clase.
//        unset($_SESSION['id'], $_SESSION['usuario']);
        Session::delete('id');
        Session::delete('usuario');

        // En CWM, esto eliminaría la cookie.
        // setcookie('_token', null, time() - 3600);
    }

    /**
     * Indica si el usuario está autenticado.
     *
     * @return bool
     */
    public static function isLogged()
    {
//        return isset($_SESSION['id']);
        return Session::has('id');

        // En CWM, acá verificaríamos si el token es correcto.
    }
}





