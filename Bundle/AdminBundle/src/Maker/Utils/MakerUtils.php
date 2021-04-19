<?php

namespace Umbrella\AdminBundle\Maker\Utils;

/**
 * Class MakerUtils
 */
class MakerUtils
{
    /**
     * Looks for suffixes in strings in a case-insensitive way.
     */
    public static function hasSuffix($value, $suffix)
    {
        return 0 === strcasecmp($suffix, substr($value, -strlen($suffix)));
    }

    /**
     * Ensures that the given ends with the given suffix. If the string
     * already contains the suffix, it's not added twice. It's case-insensitive
     * (e.g. value: 'Foocommand' suffix: 'Command' -> result: 'FooCommand').
     */
    public static function addSuffix($value, $suffix)
    {
        return self::removeSuffix($value, $suffix) . $suffix;
    }

    /**
     * Ensures that the given doesn't end with the given suffix. If the
     * contains the suffix multiple times, only the last one is removed.
     * It's case-insensitive (e.g. value: 'Foocommand' suffix: 'Command' -> result: 'Foo'.
     */
    public static function removeSuffix($value, $suffix)
    {
        return self::hasSuffix($value, $suffix) ? substr($value, 0, -strlen($suffix)) : $value;
    }

    /**
     * Transforms the given into the format commonly used by PHP classes,
     * (e.g. `app:do_this-and_that` -> `AppDoThisAndThat`) but it doesn't check
     * the validity of the class name.
     */
    public static function asClassName($value, $suffix = '')
    {
        $value = trim($value);
        $value = str_replace(['-', '_', '.', ':'], ' ', $value);
        $value = ucwords($value);
        $value = str_replace(' ', '', $value);
        $value = ucfirst($value);
        $value = self::addSuffix($value, $suffix);

        return $value;
    }

    /**
     * Transforms the given into the format commonly used by Twig variables
     * (e.g. `BlogPostType` -> `blog_post_type`).
     */
    public static function asSnakeCase($value)
    {
        $value = trim($value);
        $value = preg_replace('/[^a-zA-Z0-9_]/', '_', $value);
        $value = preg_replace('/(?<=\\w)([A-Z])/', '_$1', $value);
        $value = preg_replace('/_{2,}/', '_', $value);
        $value = strtolower($value);

        return $value;
    }

    /**
     * Transforms the given into the format commonly used for routes names
     * (e.g. `App\Admin\BlogPost` -> `app_admin_blogpost`).
     */
    public static function asRouteName($value)
    {
        $value = trim($value);
        $parts = explode('\\', $value);
        $tmp = [];
        foreach ($parts as $part) {
            $tmp[] = strtolower($part);
        }
        $value = implode('_', $tmp);

        return $value;
    }

    /**
     * Transforms the given into the format commonly used for routes path
     * (e.g. `BlogPost` -> `blog_post`).
     */
    public static function asRoutePath($value)
    {
        return self::asSnakeCase($value);
    }

    /**
     * (e.g. `AppBundle/Entity` -> `AppBundle\Entity`).
     */
    public static function asNamespace($value)
    {
        return str_replace('/', '\\', $value);
    }

    /**
     * (e.g. `Foo\Bar` -> `Foo/Bar`).
     */
    public static function asFilePath(string $value): string
    {
        return str_replace('\\', '/', $value);
    }

    /**
     * (e.g. `AppBundle\Entity\Pizza` -> `Pizza`).
     */
    public static function getShortClassName($fullClassName)
    {
        if (empty(self::getNamespace($fullClassName))) {
            return $fullClassName;
        }

        return substr($fullClassName, strrpos($fullClassName, '\\') + 1);
    }

    /**
     * (e.g. `AppBundle\Entity\Pizza` -> `AppBundle\Entity`).
     */
    public static function getNamespace($fullClassName)
    {
        return substr($fullClassName, 0, strrpos($fullClassName, '\\'));
    }
}
