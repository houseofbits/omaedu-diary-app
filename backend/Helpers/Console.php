<?php

namespace Backend\Helpers;

class Console
{
    public static function printLn(string $str, $type = 'i'): void
    {
        switch ($type) {
            case 'e': //error
                echo "\033[31m$str \033[0m\n";
                break;
            case 's': //success
                echo "\033[32m$str \033[0m\n";
                break;
            case 'w': //warning
                echo "\033[33m$str \033[0m\n";
                break;
            case 'i': //info
                echo "\033[36m$str \033[0m\n";
                break;
            default:
                # code...
                break;
        }
    }
}