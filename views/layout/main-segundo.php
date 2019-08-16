<!DOCTYPE html>
<html>
<head>
    <title><?= isset($_title) ? $_title : 'Admin Da Vinci';?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= \DaVinci\Core\App::urlTo('css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?= \DaVinci\Core\App::urlTo('css/estilos.css');?>">
</head>
<body>
    <div class="container main-content">
        <?php // @{{content}} va a reemplazarse por el contenido de cada vista :) ?>
        @{{content}}
    </div>
    
    <script src="<?= \DaVinci\Core\App::urlTo('js/jquery-3.2.1.js');?>"></script>
    <script src="<?= \DaVinci\Core\App::urlTo('js/bootstrap.js');?>"></script>
</body>
</html>
