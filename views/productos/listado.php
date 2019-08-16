<?php
use Application\Core\App;
use Application\Models\Producto;

// Indicamos utilizando phpDoc cuáles son las variables que esperamos
// que esta vista reciba.
/** @var Producto[] $productos */
/** @var string $mensaje */
?>

<h1>Listado de Productos</h1>

<?php
if($mensaje !== null):
?>
<div class="alert alert-success"><?= $mensaje;?></div>
<?php
endif; ?>

<a href="productos/crear">Crear nuevo Producto</a>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Marca</th>
        <th>Categoría</th>
        <th>Precio</th>
        <th>Características</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($productos as $producto): ?>
    <tr>
        <td><?= $producto->getIdProducto();?></td>
        <td><?= $producto->getNombre();?></td>
        <td><?= $producto->getMarca()->getMarca();?></td>
        <td><?= $producto->getIdCategoria();?></td>
        <td>$ <?= $producto->getPrecio();?></td>
        <td>
            <ul>
            <?php
            foreach($producto->getCaracteristicas() as $caracteristica): ?>
                <li><b><?= $caracteristica->getNombre();?>:</b> <?= $caracteristica->getValor();?></li>
            <?php
            endforeach; ?>
            </ul>
        </td>
        <td>
            <a href="<?= App::urlTo('productos/' . $producto->getSlug());?>">Ver detalles</a>
            <form action="<?= App::urlTo('productos/' . $producto->getIdProducto() . '/eliminar');?>" method="post" class="form-eliminar">
                <button class="btn btn-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>

<script>
window.addEventListener('DOMContentLoaded', function() {
    const formsEliminar = document.querySelectorAll('.form-eliminar');

    formsEliminar.forEach(el => {
        el.addEventListener('submit', function(ev) {
            if(!confirm('¿Estás SEGURO que querés eliminar este producto? Esta acción es irreversible.')) {
                ev.preventDefault();
            }
        });
    });
});
</script>