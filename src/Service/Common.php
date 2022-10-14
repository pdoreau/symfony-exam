<?php

namespace App\Service;

class Common
{
    /**
     * Iterate over $array and copy every element into the result array. Using the reference of $result
     * (& operator) allows to return the same elements of $array.
     *
     * @param array<mixed> $array
     * @return array<mixed>
     */
    public static function boo(array $array): array
    {
        $result = [];
        array_walk_recursive($array, function ($a) use (&$result) {
            $result[] = $a;
        });

        return $result;
    }

    /**
     * Merge elements of $array1 with another element which has the 'k' value of $array2 as key, and the 'v' value of
     * $array2 as value.
     *
     * @param array<mixed> $array1
     * @param array<mixed> $array2
     * @return array<mixed>
     */
    public static function foo(array $array1, array $array2): array
    {
        return [...$array1, $array2['k'] => $array2['v']];
    }

    /**
     * Return true if all the keys of $array1 have been found in values of $array2, return false otherwise
     *
     * @param array<mixed> $array1
     * @param array<mixed> $array2
     * @return bool
     */
    public static function bar(array $array1, array $array2): bool
    {
        $r = array_filter(array_keys($array1), fn ($k) => !in_array($k, $array2));

        return count($r) == 0;
    }
}
