<?php

// FRONT CONTROLLER

//  General settings
ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();


define('ROOT', dirname(__FILE__));
require_once(ROOT.'/function/Autoload.php');

// Router call
$router = new router();
$router->run();
