<?php


namespace OldElephant\Engines\IO;


class IO
{
    /*
     * filter input
     */
    public static function filter_input($arr,$key)
    {
        $value=htmlspecialchars($arr[$key]);
        return $value;
    }
}