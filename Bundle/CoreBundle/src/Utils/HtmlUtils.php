<?php

namespace Umbrella\CoreBundle\Utils;

class HtmlUtils
{
    public static function to_attr(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= sprintf('%s="%s" ', $key, self::escape($value, 'html_attr'));
        }

        return $html;
    }

    /**
     * @see Twig\Extension\EscaperExtension
     *
     * @return mixed|string
     *
     * @param null|string $string
     */
    public static function escape(?string $string, string $strategy = 'html', string $charset = 'UTF-8')
    {
        if (!\is_string($string)) {
            if (\is_object($string) && method_exists($string, '__toString')) {
                $string = (string) $string;
            } else {
                return $string;
            }
        }

        if ('' === $string) {
            return '';
        }

        switch ($strategy) {
            case 'html':
                // see https://secure.php.net/htmlspecialchars

                // Using a static variable to avoid initializing the array
                // each time the function is called. Moving the declaration on the
                // top of the function slow downs other escaping strategies.
                static $htmlspecialcharsCharsets = [
                    'ISO-8859-1' => true, 'ISO8859-1' => true,
                    'ISO-8859-15' => true, 'ISO8859-15' => true,
                    'utf-8' => true, 'UTF-8' => true,
                    'CP866' => true, 'IBM866' => true, '866' => true,
                    'CP1251' => true, 'WINDOWS-1251' => true, 'WIN-1251' => true,
                    '1251' => true,
                    'CP1252' => true, 'WINDOWS-1252' => true, '1252' => true,
                    'KOI8-R' => true, 'KOI8-RU' => true, 'KOI8R' => true,
                    'BIG5' => true, '950' => true,
                    'GB2312' => true, '936' => true,
                    'BIG5-HKSCS' => true,
                    'SHIFT_JIS' => true, 'SJIS' => true, '932' => true,
                    'EUC-JP' => true, 'EUCJP' => true,
                    'ISO8859-5' => true, 'ISO-8859-5' => true, 'MACROMAN' => true,
                ];

                if (isset($htmlspecialcharsCharsets[$charset])) {
                    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
                }

                if (isset($htmlspecialcharsCharsets[strtoupper($charset)])) {
                    // cache the lowercase variant for future iterations
                    $htmlspecialcharsCharsets[$charset] = true;

                    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
                }

                $string = iconv($charset, 'UTF-8', $string);
                $string = htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                return iconv($charset, 'UTF-8', $string);

            case 'js':
                // escape all non-alphanumeric characters
                // into their \x or \uHHHH representations
                if ('UTF-8' !== $charset) {
                    $string = iconv($charset, 'UTF-8', $string);
                }

                if (!preg_match('//u', $string)) {
                    throw new \RuntimeException('The string to escape is not a valid UTF-8 string.');
                }

                $string = preg_replace_callback('#[^a-zA-Z0-9,\._]#Su', function ($matches) {
                    $char = $matches[0];

                    /*
                     * A few characters have short escape sequences in JSON and JavaScript.
                     * Escape sequences supported only by JavaScript, not JSON, are omitted.
                     * \" is also supported but omitted, because the resulting string is not HTML safe.
                     */
                    static $shortMap = [
                        '\\' => '\\\\',
                        '/' => '\\/',
                        "\x08" => '\b',
                        "\x0C" => '\f',
                        "\x0A" => '\n',
                        "\x0D" => '\r',
                        "\x09" => '\t',
                    ];

                    if (isset($shortMap[$char])) {
                        return $shortMap[$char];
                    }

                    $codepoint = mb_ord($char);
                    if (0x10000 > $codepoint) {
                        return sprintf('\u%04X', $codepoint);
                    }

                    // Split characters outside the BMP into surrogate pairs
                    // https://tools.ietf.org/html/rfc2781.html#section-2.1
                    $u = $codepoint - 0x10000;
                    $high = 0xD800 | ($u >> 10);
                    $low = 0xDC00 | ($u & 0x3FF);

                    return sprintf('\u%04X\u%04X', $high, $low);
                }, $string);

                if ('UTF-8' !== $charset) {
                    $string = iconv('UTF-8', $charset, $string);
                }

                return $string;

            case 'css':
                if ('UTF-8' !== $charset) {
                    $string = iconv($charset, 'UTF-8', $string);
                }

                if (!preg_match('//u', $string)) {
                    throw new \RuntimeException('The string to escape is not a valid UTF-8 string.');
                }

                $string = preg_replace_callback('#[^a-zA-Z0-9]#Su', function ($matches) {
                    $char = $matches[0];

                    return sprintf('\\%X ', 1 === \strlen($char) ? \ord($char) : mb_ord($char, 'UTF-8'));
                }, $string);

                if ('UTF-8' !== $charset) {
                    $string = iconv('UTF-8', $charset, $string);
                }

                return $string;

            case 'html_attr':
                if ('UTF-8' !== $charset) {
                    $string = iconv($charset, 'UTF-8', $string);
                }

                if (!preg_match('//u', $string)) {
                    throw new \RuntimeException('The string to escape is not a valid UTF-8 string.');
                }

                $string = preg_replace_callback('#[^a-zA-Z0-9,\.\-_]#Su', function ($matches) {
                    /**
                     * This function is adapted from code coming from Zend Framework.
                     *
                     * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (https://www.zend.com)
                     * @license   https://framework.zend.com/license/new-bsd New BSD License
                     */
                    $chr = $matches[0];
                    $ord = \ord($chr);

                    /*
                     * The following replaces characters undefined in HTML with the
                     * hex entity for the Unicode replacement character.
                     */
                    if (($ord <= 0x1F && "\t" != $chr && "\n" != $chr && "\r" != $chr) || ($ord >= 0x7F && $ord <= 0x9F)) {
                        return '&#xFFFD;';
                    }

                    /*
                     * Check if the current character to escape has a name entity we should
                     * replace it with while grabbing the hex value of the character.
                     */
                    if (1 === \strlen($chr)) {
                        /*
                         * While HTML supports far more named entities, the lowest common denominator
                         * has become HTML5's XML Serialisation which is restricted to the those named
                         * entities that XML supports. Using HTML entities would result in this error:
                         *     XML Parsing Error: undefined entity
                         */
                        static $entityMap = [
                            34 => '&quot;', /* quotation mark */
                            38 => '&amp;',  /* ampersand */
                            60 => '&lt;',   /* less-than sign */
                            62 => '&gt;',   /* greater-than sign */
                        ];

                        if (isset($entityMap[$ord])) {
                            return $entityMap[$ord];
                        }

                        return sprintf('&#x%02X;', $ord);
                    }

                    /*
                     * Per OWASP recommendations, we'll use hex entities for any other
                     * characters where a named entity does not exist.
                     */
                    return sprintf('&#x%04X;', mb_ord($chr, 'UTF-8'));
                }, $string);

                if ('UTF-8' !== $charset) {
                    $string = iconv('UTF-8', $charset, $string);
                }

                return $string;

            case 'url':
                return rawurlencode($string);

            default:
                throw new \RuntimeException(sprintf('Invalid escaping strategy "%s".', $strategy));
        }
    }
}
