<?php
use DaVinci\Auth\Auth;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= isset($_title) ? $_title : 'Admin Da Vinci';?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= \Application\Core\App::urlTo('css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?= \Application\Core\App::urlTo('css/estilos.css');?>">
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?= \Application\Core\App::urlTo('');?>">Da Vinci</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= \Application\Core\App::urlTo('quienes-somos');?>">Quiénes Somos</a></li>
                <?php
                if(Auth::isLogged()): ?>
                    <li><a href="<?= \Application\Core\App::urlTo('productos');?>">Productos</a></li>
                    <li><a href="<?= \Application\Core\App::urlTo('logout');?>">Cerrar Sesión</a></li>
                <?php
                else: ?>
                    <li><a href="<?= \Application\Core\App::urlTo('login');?>">Iniciar Sesión</a></li>
                <?php
                endif; ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container main-content">
    <?php // @{{content}} va a reemplazarse por el contenido de cada vista :) ?>
    @{{content}}
</div>

<div class="footer">
    Da Vinci &copy; <?= date('Y');?>
</div>
<script src="<?= \Application\Core\App::urlTo('js/jquery-3.2.1.js');?>"></script>
<script src="<?= \Application\Core\App::urlTo('js/bootstrap.js');?>"></script>
</body>
</html>
