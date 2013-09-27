<?php
$app->mount('/projects', new LogMon\Controller\ProjectsController());
$app->mount('/log', new LogMon\Controller\LogController());


?>
