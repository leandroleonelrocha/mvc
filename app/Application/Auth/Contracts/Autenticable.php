<?php
namespace Application\Auth\Contracts;

/**
 * Interfaz que define un contrato para que la clase
 * pueda usarse para autenticar con la clase Auth.
 */
interface Autenticable
{
    /**
     * Carga los datos del usuario, obtenidos a partir
     * del $usuario.
     *
     * @param string $usuario
     * @return bool
     */
    public function buscarPorUsuario($usuario) : bool;

    /**
     * Retorna el password.
     *
     * @return string
     */
    public function getPassword();
    
    /**
     * Retorna el id.
     *
     * @return int
     */
    public function getId();
    
    /**
     * Retorna el usuario.
     *
     * @return string
     */
    public function getUsuario();
}