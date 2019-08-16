<?php
namespace DaVinci\Controllers;


use DaVinci\Auth\Auth;
use DaVinci\Core\App;
use DaVinci\Core\View;
use DaVinci\Models\Usuario;
use DaVinci\Storage\Session;
use DaVinci\Validation\Validator;

class AuthController extends BaseController
{
    public function loginForm()
    {
//        $errores = $_SESSION['errores'] ?? [];
//        $oldData = $_SESSION['old_data'] ?? [];
//        unset($_SESSION['errores'], $_SESSION['old_data']);

//        $errores = Session::get('errores');
//        $oldData = Session::get('old_data');
//        Session::delete('errores');
//        Session::delete('old_data');

        $errores = Session::flash('errores');
        $oldData = Session::flash('old_data');

        View::render('auth/login', compact('errores', 'oldData'));
    }

    public function doLogin()
    {
        $validator = new Validator($_POST, [
            'usuario' => ['required', 'min:2'],
            'password' => ['required']
        ]);

        if(!$validator->passes()) {
//            $_SESSION['old_data'] = $_POST;
//            $_SESSION['errores'] = $validator->getErrores();
            Session::set('errores', $validator->getErrores());
            Session::set('old_data', $_POST);
            App::redirect('/login');
        }

        // Tratamos de autenticar al usuario.
        $auth = new Auth(new Usuario());

        if($auth->login($_POST['usuario'], $_POST['password']) !== 0) {
//            $_SESSION['old_data'] = $_POST;
//            $_SESSION['errores'] = ['login' => 'Las credenciales ingresadas no coinciden con nuestros registros.'];
            Session::set('errores', ['login' => 'Las credenciales ingresadas no coinciden con nuestros registros.']);
            Session::set('old_data', $_POST);
            App::redirect('/login');
        }

        App::redirect('/productos');
    }

    public function doLogout()
    {
        $this->requiresAuth();

        $auth = new Auth(new Usuario());
        $auth->logout();
        App::redirect('/');
    }
}