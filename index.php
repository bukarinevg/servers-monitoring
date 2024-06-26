<?php 
/**
 * This entry point is responsible for loading the application.
 */

require_once 'vendor/autoload.php';
$config = require 'config/config.php';

$app = new app\source\App($config);
$app->run();



