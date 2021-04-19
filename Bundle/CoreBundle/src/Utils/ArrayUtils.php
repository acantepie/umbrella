<?php

namespace Umbrella\CoreBundle\Utils;

/**
 * Class ArrayUtils.
 */
class ArrayUtils
{
    /**
     * Return true if all element of array $search are in array $all
     */
    public static function contains_all(array $search, array $all): bool
    {
        return count(array_intersect($search, $all)) === count($search);
    }

    public static function array_merge_recursive()
    {
        $args = func_get_args();

        return self::_array_merge_recursive($args);
    }

    // source : https://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/drupal_array_merge_deep_array/7.x
    private static function _array_merge_recursive(array $arrays): array
    {
        $result = [];
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                // Renumber integer keys as array_merge_recursive() does. Note that PHP
                // automatically converts array keys that are integer strings (e.g., '1')
                // to integers.
                if (is_integer($key)) {
                    $result[] = $value;
                } elseif (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
                    $result[$key] = self::_array_merge_recursive([
                        $result[$key],
                        $value,
                    ]);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    public static function array_map_recursive($f, array $xs): array
    {
        $out = [];
        foreach ($xs as $k => $x) {
            $out[$k] = (is_array($x)) ? self::array_map_recursive($f, $x) : $f($x);
        }

        return $out;
    }

    /**
     * Convert
     * [
     *  a => [
     *      b => 1,
     *      c => 2
     *  ]
     * ]
     * to
     * [
     *  a.b => 1,
     *  a.c => 2
     * ]
     */
    public static function remap_nested_array(array $nested, string $baseNs = '', array $stopRules = []): array
    {
        $r = [];
        self::map_recursive_nested_array($nested, $baseNs, $stopRules, $r);

        return $r;
    }

    private static function map_recursive_nested_array($a, string $currentNs = '', array $stopRules = [], array &$result)
    {
        if (is_array($a) && !in_array($currentNs, $stopRules)) {
            foreach ($a as $key => $value) {
                $ns = empty($currentNs) ? $key : sprintf('%s.%s', $currentNs, $key);
                self::map_recursive_nested_array($value, $ns, $stopRules, $result);
            }
        } else {
            $result[$currentNs] = $a;
        }
    }
}
