<?php
//autoload và điều hướng
include '../Apps/bootstrap.php';

$router = new Apps_Libs_Router(__DIR__);
$router->router();

