<?php

spl_autoload_register(function($classname){
    $exp= str_replace("_", "/", $classname);
    $path= str_replace("Apps", "", dirname(__FILE__));
//    echo dirname(__FILE__);
//    echo "<br>";
//    var_dump($path);
//    echo "<br>";
    include_once $path."/".$exp.".php";
//    var_dump($path."/".$exp.".php");
});