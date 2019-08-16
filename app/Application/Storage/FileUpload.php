<?php
namespace Application\Storage;


class FileUpload
{
    /** @var array */
    protected $file;
    /** @var string */
    protected $fileName;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Sube el archivo al $path indicado.
     *
     * @param string $path
     * @return FileUpload La propia instancia.
     */
    public function upload(string $path)
    {
        $this->generateName();
        move_uploaded_file($this->file['tmp_name'], $path . $this->fileName);

        // Retornan $this permite que se encadenen métodos en la
        // llamada.
        return $this;
    }

    /**
     * Genera un nombre para el archivo, agregando el timestamp adelante
     * con un "_".
     */
    public function generateName()
    {
        $this->fileName = time() . "_" . $this->file['name'];
    }

    /**
     * @return array
     */
    public function getFile(): array
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}