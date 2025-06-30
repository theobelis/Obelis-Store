<?php
spl_autoload_register(function($class){
    // Buscar en Config/App
    if (file_exists("Config/App/".$class.".php")) {
        require_once "Config/App/" . $class . ".php";
        return;
    }
    
    // Buscar en Models
    if (file_exists("Models/".$class.".php")) {
        require_once "Models/" . $class . ".php";
        return;
    }
})
?>