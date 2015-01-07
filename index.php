<?php
define(ROOT_PATH , dirname(__FILE__));
$mainConfig = require ROOT_PATH . "/config/config.inc.php";
require_once("framework/app.php");

$freeApp = App::getInstance($mainConfig);
$freeApp -> run();