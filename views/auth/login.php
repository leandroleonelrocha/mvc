<?php
use Application\Core\App;

/** @var array $errores */
/** @var array $oldData */
?>
<h1>Iniciar Sesión</h1>
<p>Ingresá tus credenciales para ingresar al sitio.</p>

<?php
if(isset($errores['login'])): ?>
<div class="alert alert-danger"><?= $errores['login'];?></div>
<?php
endif; ?>

<form action="<?= App::urlTo('login');?>" method="post">
    <div class="form-group">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" class="form-control" value="<?= $oldData['usuario'] ?? '';?>">
        <?php
        if(isset($errores['usuario'])): ?>
        <div class="alert alert-danger"><?= $errores['usuario'] [0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control">
        <?php
        if(isset($errores['password'])): ?>
            <div class="alert alert-danger"><?= $errores['password'][0];?></div>
        <?php
        endif; ?>
    </div>
    <button class="btn btn-primary btn-block">Ingresar</button>
</form>
