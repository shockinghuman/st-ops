<?php

namespace SH\STOPS\Operations;

trait Config
{
    public static function return(string $key)
    {
        $config = require __DIR__.'/../../../../config.php';
        return $config[$key];
    }
}