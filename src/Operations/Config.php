<?php

namespace SH\STOPS\Operations;

trait Config
{
    /**
     * @throws \Exception
     */
    public static function return(string $key)
    {
        if (file_exists(__DIR__.'/../../../../../config.php')){
            $config = require __DIR__.'/../../../../../config.php';
            return $config[$key];
        }
        throw new \Exception("No Config File");
    }
}