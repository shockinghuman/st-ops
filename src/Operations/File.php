<?php

namespace SH\STOPS\Operations;

class File
{
    /**
     * Append lines to a specified file
     * @param string $filePath
     * @param array $lines
     * @param string $method
     * @return void
     */
    protected static function process(string $filePath, array $lines, string $method = 'a+')
    {
        $fp = fopen($filePath, $method);
        flock($fp, LOCK_EX);
        foreach($lines as $line) {
            fwrite($fp, "$line\n");
        }
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    public static function add_line(string $filePath, string $line_to_add)
    {
        self::process($filePath, ["$line_to_add"], "a+");
    }
    public static function rm_line(string $filePath, string $line_to_remove)
    {
        $data = file($filePath);

        $out = array();

        foreach($data as $line) {
            if(trim($line) !== $line_to_remove) {
                $out[] = trim($line);
            }
        }
        self::process($filePath, $out, "w+");
    }
}