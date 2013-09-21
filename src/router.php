<?php
require_once ROOT . 'src/controller/indexController.php';
require_once ROOT . 'src/controller/logController.php';
$app->mount('/projects', new LogMon\Controller\ProjectsController());
$app->mount('/log', new LogMon\Controller\LogController());


?>
