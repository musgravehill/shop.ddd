<?php
require(__DIR__ . '/../../../vendor/autoload.php');

function my_custom_autoloader($className)
{
    //  app\components\*
    $path = str_replace('app', '', $className);  // \components\*
    $path = str_replace('\\', '/', $path);  // /components/*
    $file = __DIR__ . '/../../../' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// add a new autoloader by passing a callable into spl_autoload_register()
spl_autoload_register('my_custom_autoloader');
