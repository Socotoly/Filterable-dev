<?php


namespace Socotoly\Filterable\Support;


class Helpers
{
    public static function arrayToLowerCase(array $array, bool $keysOnly = true): array
    {
        $loweredArray = [];

        foreach ($array as $key => $val) {
            $loweredArray[strtolower($key)] = $keysOnly ? $val : strtolower($val);
        }

        return $loweredArray;
    }
}
