<?php

namespace App\Helpers;

class Str {
    public static function slug(string $string)
    {
        $disallowedCharacters = '/[^\-\s\pN\pL]+/';
        $spacesDuplicateHyphens = '/[\-\s]+/';

        $slug = mb_strtolower($string, 'UTF-8');
        $slug = preg_replace($disallowedCharacters, '', $slug);
        $slug = preg_replace($spacesDuplicateHyphens, '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    public static function token(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function toCamelCase(string $subject): string
    {
        $words = explode('_', $subject);

        $words = array_map(function ($word) {
            return ucfirst($word);
        }, $words);

        $subject = lcfirst(implode('', $words));

        return $subject;
    }
}
