<?php

namespace Umbrella\CoreBundle\Utils;

/**
 * Class Utils
 */
class Utils
{
    const ALPHANUM = 'abcdefghijklmnopqrstuvwxyz0123456789';
    const ALPHA_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function type_class_to_id(string $typeClass): string
    {
        $ns = preg_replace('/Type$/', '', $typeClass);
        $name = str_replace('\\', '_', $ns);

        return \function_exists('mb_strtolower') && preg_match('//u', $name) ? mb_strtolower($name, 'UTF-8') : strtolower($name);
    }

    /**
     * @see https://stackoverflow.com/questions/6167279/converting-a-simplexml-object-to-an-array?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
     */
    public static function xml_2_array($xmlObject): array
    {
        return json_decode(json_encode((array) $xmlObject), true);
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

    public static function random(int $length = 8, string $characters = self::ALPHANUM): string
    {
        $str = '';

        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }

        return str_shuffle($str);
    }
}
