<?php
$app->mount('/projects', new LogMon\Controller\ProjectsController());
$app->mount('/log', new LogMon\Controller\LogController());
$app->mount('/API/v1/auth/', new LogMon\Controller\Auth());
