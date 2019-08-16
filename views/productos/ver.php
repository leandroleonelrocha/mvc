<?php
use DaVinci\Core\App;
use DaVinci\Models\Producto;

/** @var Producto $producto */
?>

<h1><?= $producto->getNombre();?></h1>

<?php
if(!empty($producto->getImagen()) && file_exists(App::getPublicPath() . '/imgs/' . $producto->getImagen())): ?>
    <img src="<?= App::urlTo('/imgs/' . $producto->getImagen())?>" alt="Imagen de <?= $producto->getNombre();?>" width="250">
<?php
else: ?>
    Sin imagen
<?php
endif;?>

<dl>
    <dt>Precio</dt>
    <dd>$ <?= $producto->getPrecio();?></dd>
    <dt>Marca</dt>
    <dd><?= $producto->getMarca()->getMarca();?></dd>
    <dt>Categoría</dt>
    <dd><?= $producto->getIdCategoria();?></dd>
</dl>