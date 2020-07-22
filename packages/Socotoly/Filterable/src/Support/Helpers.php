<?php


namespace Socotoly\Filterable\Support;


class Helpers
{
    public static function arrayToLowerCase(array $array, bool $keysOnly = true): array
    {
        $loweredArray = [];

        foreach ($array as $key => $val) {
            is_array($val) ? $loweredArray[strtolower($key)] = self::arrayToLowerCase($val, $keysOnly) :
            $loweredArray[strtolower($key)] = $keysOnly ? $val : strtolower($val);
        }

        return $loweredArray;
    }

    public static function classToLowerCase(object $class): string
    {
        return strtolower(class_basename($class));
    }
}
