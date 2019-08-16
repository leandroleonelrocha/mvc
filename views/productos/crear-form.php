<?php
use DaVinci\Core\App;

/** @var array $errores */
/** @var array $oldData */
/** @var \DaVinci\Models\Caracteristica[] $caracteristicas */
?>

<h1>Crear nuevo Producto</h1>

<form action="<?= App::urlTo('productos/crear');?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $oldData['nombre'] ?? '';?>">
        <?php
        if(isset($errores['nombre'])): ?>
            <div class="alert alert-danger"><?= $errores['nombre'][0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="id_marca">Marca (ID)</label>
        <input type="text" id="id_marca" name="id_marca" class="form-control" value="<?= $oldData['id_marca'] ?? '';?>">
    </div>
    <div class="form-group">
        <label for="id_categoria">Categoría (ID)</label>
        <input type="text" id="id_categoria" name="id_categoria" class="form-control" value="<?= $oldData['id_categoria'] ?? '';?>">
    </div>
    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="text" id="precio" name="precio" class="form-control" value="<?= $oldData['precio'] ?? '';?>">
        <?php
        if(isset($errores['precio'])): ?>
            <div class="alert alert-danger"><?= $errores['precio'][0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="text" id="stock" name="stock" class="form-control" value="<?= $oldData['stock'] ?? '';?>">
    </div>
    <div class="form-group">
        <label for="cuotas_sin_interes">Cuotas sin interés</label>
        <input type="text" id="cuotas_sin_interes" name="cuotas_sin_interes" class="form-control"value="<?= $oldData['cuotas_sin_interes'] ?? '';?>">
    </div>
    <div class="form-group">
        <label for="imagen">Imagen</label>
        <input type="file" id="imagen" name="imagen" class="form-control">
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control"><?= $oldData['descripcion'] ?? '';?></textarea>
    </div>
    <fieldset>
        <legend>Características</legend>
            <?php
            foreach($caracteristicas as $caracteristica): ?>
            <div>
                <label><input type="checkbox" name="caracteristicas[]" value="<?= $caracteristica->getIdCaracteristica();?>"> <?= $caracteristica->getNombre();?></label>
                <!-- Le ponemos como índice a cada name del input de texto el mismo valor que usamos para el value del checkbox que le corresponde. De esta manera, en el php, vamos a poder fácilmente detectar el valor que le corresponde a cada checkbox seleccionado. -->
                <input type="text" name="caracteristicasValores[<?= $caracteristica->getIdCaracteristica();?>]">
            </div>
            <?php
            endforeach; ?>
    </fieldset>
    <button class="btn btn-primary btn-block">Grabar :D</button>
</form>