<?php
$app->mount('/projects', new LogMon\Controller\ProjectsController());
$app->mount('/log', new LogMon\Controller\LogController());
$app->mount('/', new LogMon\Controller\Auth());
