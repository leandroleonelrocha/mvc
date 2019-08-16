<?php
namespace Application\Controllers;

use Application\Auth\Auth;
use Application\Core\App;
use Application\Core\Route;
use Application\Core\View;
use Application\Helpers\StringHelper;
use Application\Models\Caracteristica;
use Application\Models\Producto;
use Application\Storage\FileUpload;
use Application\Storage\Session;
use Application\Validation\Validator;

class ProductosController extends BaseController
{
    public function listado()
    {
        $this->requiresAuth();

        $producto = new Producto();
        $productos = $producto->getAll();

//        $mensaje = $_SESSION['mensaje'] ?? null;
//        unset($_SESSION['mensaje']);
//        $mensaje = Session::get('mensaje');
//        Session::delete('mensaje');
        $mensaje = Session::flash('mensaje');

        // El segundo parámetro, opcional, del método render permite
        // pasar variables a la vista.
        // Dentro del array que pasamos, el índice del mismo va a ser
        // lo que el render utilice para definir como nombre de variable
        // para la vista. Y el valor que le asociemos, es el valor que
        // esa variable va a tener.
        /*View::render('productos/listado', [
            // Esto diría que en la vista quiero tener una variable
            // "$prods" que contenga lo que actualmente tiene la variable
            // $productos.
//            'prods' => $productos
            'productos' => $productos
        ]);*/

        // compact() es una función nativa de php que genera una array
        // asociativo a partir de las variables indicadas como strings
        // por parámetro.
        // Por ejemplo, si hacemos:
        //      compact('productos')
        // La función retorna:
        //      ['productos' => $productos]
//        View::render('productos/listado', compact('productos', 'mensaje'));
        View::renderWithLayout('productos/listado', compact('productos', 'mensaje'));
        // Para la API...
//        View::renderJson([
//            'status' => 0,
//            'data' => $productos
//        ]);
    }

    public function verDetalle()
    {
        $this->requiresAuth();

        // A este controller accedemos desde la ruta:
        // productos/{id}
        // Para obtener el parámetro "id", podemos usar la clase
        // Route::getUrlParameters();
        // que retorna un array con todos los parámetros de la url,
        // donde el nombre del parámetro (el texto entre llaves) es
        // el índice.
        $parameters = Route::getUrlParameters();
        $slug = $parameters['id'];

        $producto = new Producto;
//        $producto->getByPk($slug);
        $producto->getBySlug($slug);

        View::render('productos/ver', compact('producto'));
        // Si queremos usar esta ruta como un endpoint para una API...
//        View::renderJson([
//            'status' => 0,
//            'data' => $producto
//        ]);
    }

    public function crearForm()
    {
        $this->requiresAuth();

//        $errores = $_SESSION['errores'] ?? [];
//        $oldData = $_SESSION['old_data'] ?? [];
//        unset($_SESSION['errores'], $_SESSION['old_data']);

//        $errores = Session::get('errores');
//        $oldData = Session::get('old_data');
//        Session::delete('errores');
//        Session::delete('old_data');

        $errores = Session::flash('errores');
        $oldData = Session::flash('old_data');
        $caracteristica = new Caracteristica();
        $caracteristicas = $caracteristica->getAll();

        View::render('productos/crear-form', compact('errores', 'oldData', 'caracteristicas'));
    }

    public function grabar()
    {
        $this->requiresAuth();
//
//        echo "<pre>";
//        print_r($_POST);
//        echo "</pre>";
//        exit;

        $validator = new Validator($_POST, [
            'nombre' => ['required', 'min:2'],
            'precio' => ['required', 'numeric']
        ]);

        if(!$validator->passes()) {
//            $_SESSION['errores'] = $validator->getErrores();
//            $_SESSION['old_data'] = $_POST;
            Session::set('errores', $validator->getErrores());
            Session::set('old_data', $_POST);
            App::redirect('productos/crear');
        }

        // Alta de la imagen.
        $archivo = new FileUpload($_FILES['imagen']);
        $archivo->upload(App::getPublicPath() . '/imgs/');
        $nombreImagen = $archivo->getFileName();
        // Encadenando...
        $nombreImagen = $archivo
            ->upload(App::getPublicPath() . '/imgs/')
            ->getFileName();

//        $data = $_POST;
        $data = [
            'nombre' => $_POST['nombre'],
            'precio' => $_POST['precio'],
            'id_marca' => $_POST['id_marca'],
            'id_categoria' => $_POST['id_categoria'],
            'descripcion' => $_POST['descripcion'],
            'stock' => $_POST['stock'],
            'cuotas_sin_interes' => $_POST['cuotas_sin_interes'],
        ];
        $data['imagen'] = $nombreImagen;
        $data['slug'] = StringHelper::slug($data['nombre']);

        $producto = new Producto();

        if($producto->create($data)) {
            // Ahora que grabó el producto, podemos seguir grabando las
            // características.
            // Preparamos las características.
            $caracteristicas = [];
            foreach($_POST['caracteristicas'] as $idCarac) {
                // Leemos el id de la característica, y su valor de los
                // datos por POST que están asociados al id.
                $caracteristicas[] = [
                    'id_caracteristica' => $idCarac,
                    'valor' => $_POST['caracteristicasValores'][$idCarac]
                ];
            }

            if($producto->grabarCaracteristicas($caracteristicas)) {
//            header('Location: ' . App::urlTo('productos'));
//            $_SESSION['mensaje'] = "El producto fue creado exitosamente! :D";
                Session::set('mensaje', "El producto fue creado exitosamente! :D");
                App::redirect('productos');
            }
        }
//            $_SESSION['errores'] = ['db' => 'Error al grabar el producto en la base de datos.'];
//            $_SESSION['old_data'] = $_POST;
        Session::set('errores', ['db' => 'Error al grabar el producto en la base de datos.']);
        Session::set('old_data', $_POST);
        App::redirect('productos/crear');
    }

    public function eliminar()
    {
        $this->requiresAuth();

        $parameters = Route::getUrlParameters();
        $id = $parameters['id'];
        $prod = new Producto;
        if($prod->delete($id)) {
//            $_SESSION['mensaje'] = "El producto se eliminó exitosamente.";
            Session::set('mensaje', "El producto se eliminó exitosamente.");
            App::redirect('productos');
        } else {
            Session::set('error', "El producto se eliminó exitosamente.");
            App::redirect('productos');
        }
    }
}