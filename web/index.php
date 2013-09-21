<?php

require_once __DIR__ . '/../vendor/autoload.php';


require_once __DIR__ . '/../resources/config/default.php';

$app = require __DIR__ . '/../src/app.php';

$app->run();

?>
