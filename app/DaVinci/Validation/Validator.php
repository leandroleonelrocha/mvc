<?php
namespace DaVinci\Validation;

/**
 * Clase para validar datos.
 */
class Validator
{
    /** @var array Los datos a validar. */
    protected $data = [];
    
    /** @var array Las reglas de validación. */
    protected $reglas = [];
        
    /** @var array Los errores de validación. */
    protected $errores = [];
    
    /**
     * Crea el validador.
     *
     * @param array $data Los datos a validar.
     * @param array $reglas Las reglas a aplicar a los datos.
     */
    public function __construct($data, $reglas)
    {
        $this->data = $data;
        $this->reglas = $reglas;
        
        $this->validate();
    }
    
    /**
     * Ejecuta las validaciones.
     */
    public function validate()
    {
        // Primero que nada, recorremos el array de
        // las reglas de validación a aplicar.
        // Ej:
        // $campo = 'nombre';
        // $listaReglas = ['required']
        // $listaReglas = ['required', 'numeric']
        // $listaReglas = ['required', 'min:3']
        foreach($this->reglas as $campo => $listaReglas) {
            // Recorremos la lista de reglas para el
            // campo.
            foreach($listaReglas as $regla) {
                // Ejecutar la regla para el campo.
                $this->aplicarRegla($campo, $regla);
            }
        }
    }
    
    /**
     * Aplica una $regla de validación al $campo.
     *
     * @param string $campo
     * @param string $regla
     */
    public function aplicarRegla($campo, $regla)
    {
        // Ej
        // $campo = "nombre";
        // $regla = 'required';
        // $regla = 'numeric';
        // $regla = 'min:3';
        // Antes que nada, necesitamos verificar en 
        // qué tipo de regla estamos:
        // Caso a. 'nombreRegla'
        // Caso b. 'nombreRegla:parametro'
        // Básicamente, si hay un ":", es el caso b.
        if(strpos($regla, ':')) {
            // Separamos la regla y el parámetro.
//            $reglaData = explode(':', $regla);
            // El list() a la izquierda del =, permite
            // separar los valores de un array en 
            // variables individuales.
            // El valor de la posición 0 va para la
            // primer variable, la posición 1 va para
            // la segunda variable, etc.
            list($nombre, $dato) = explode(':', $regla);
            
            // 1. Generamos el nombre del método.
            $metodoRegla = "_" . $nombre;

            // 2. Verificamos si el método existe.
            if(!method_exists($this, $metodoRegla)) {
                throw new Exception("No existe la regla de validación <b>" . $nombre . "</b>.");
            }

            // 3. Ejecutamos la regla.
            $this->{$metodoRegla}($campo, $dato);
        } else {
            // Partiendo de eso, si recordamos que las
            // reglas son métodos (que están prefijados
            // con "_"), si quiero ejecutar una regla, 
            // necesito:
            // 1. Obtener el nombre del método.
            // 2. Verificar que el método exista.
            // 3. Ejecutar el método pasándole los datos
            //  necesarios (ej: $campo).

            // 1. Generamos el nombre del método.
            $metodoRegla = "_" . $regla;

            // 2. Verificamos si el método existe.
            if(!method_exists($this, $metodoRegla)) {
                throw new Exception("No existe la regla de validación <b>" . $regla . "</b>.");
            }

            // 3. Ejecutamos la regla.
            $this->{$metodoRegla}($campo);
        }
    }
    
    /**
     * Indica si la validación fue exitosa.
     *
     * @return bool
     */
    public function passes()
    {
        return count($this->errores) === 0;
    }
    
    /**
     * Retorna el array de errores.
     *
     * @return array
     */
    public function getErrores()
    {
        return $this->errores;
    }
    
    /**
     * Agrega un mensaje de error.
     *
     * @params string $campo El nombre del campo.
     * @params string $error El mensaje de error.
     */
    public function addError($campo, $error)
    {
        if(!isset($this->errores[$campo])) {
            $this->errores[$campo] = [];
        }
        $this->errores[$campo][] = $error;
    }
    
    /*---------------------------------
     Validaciones
     Para cada validación que queramos poder
     hacer, vamos a crear un método que se
     llame como la regla de validación, pero
     con el prefijo "_".
     Por ejemplo, si tenemos la regla
     'required', crearemos el método 
     _required().
     El "_" lo vamos a usar para indicar que
     ese método es de validación, 
     específicamente.
     *--------------------------------*/
    /**
     * Verifica que el valor no esté vacío.
     *
     * @param string $campo El nombre del índice del valor de Validator::$data a verificar. Ej: 'nombre'
     * @return bool
     */
    protected function _required($campo)
    {
        // Obtenemos el valor.
        // Recuerden que $this->data podría ser por
        // ejemplo $_POST.
        $valor = $this->data[$campo];
        if(empty($valor)) {
//            $this->errores[$campo] = 'El ' . $campo . ' no puede estar vacío.';
            $this->addError($campo, 'El ' . $campo . ' no puede estar vacío.');
            return false;
        }
        return true;
    }
    
    /**
     * Verifica que el valor sea numérico.
     *
     * @param string $campo El nombre del índice del valor de Validator::$data a verificar. Ej: 'nombre'
     * @return bool
     */
    protected function _numeric($campo)
    {
        // Obtenemos el valor.
        // Recuerden que $this->data podría ser por
        // ejemplo $_POST.
        $valor = $this->data[$campo];
        if(!is_numeric($valor)) {
//            $this->errores[$campo] = 'El ' . $campo . ' debe ser un número.';
            $this->addError($campo, 'El ' . $campo . ' debe ser un número.');
            return false;
        }
        return true;
    }
    
    /**
     * Verifica que el valor tenga al menos $min 
     * caracteres.
     *
     * @param string $campo El nombre del índice del valor de Validator::$data a verificar. Ej: 'nombre'
     * @param int $min La cantidad de caracteres mínima.
     * @return bool
     */
    protected function _min($campo, $min)
    {
        // Obtenemos el valor.
        // Recuerden que $this->data podría ser por
        // ejemplo $_POST.
        $valor = $this->data[$campo];
        if(strlen($valor) < $min) {
            $this->addError($campo, 'El ' . $campo . ' debe tener al menos ' . $min . ' caracteres.');
            return false;
        }
        return true;
    }
}





