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
}
