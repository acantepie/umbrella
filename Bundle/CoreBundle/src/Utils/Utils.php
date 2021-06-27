<?php

namespace Umbrella\CoreBundle\Utils;

class Utils
{
    public static function type_class_to_id(string $typeClass): string
    {
        $ns = preg_replace('/Type$/', '', $typeClass);
        $name = str_replace('\\', '_', $ns);

        return \function_exists('mb_strtolower') && preg_match('//u', $name) ? mb_strtolower($name, 'UTF-8') : strtolower($name);
    }

    /**
     * @see http://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
     *
     * @param string $input
     */
    public static function to_underscore($input): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    /**
     * @see Utils.js
     */
    public static function bytes_to_size($bytes, int $precision = 2): string
    {
        if (!$bytes) {
            return '0';
        }

        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
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
}
