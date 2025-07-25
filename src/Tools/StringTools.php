<?php

namespace App\Models;

class StringTools
{
    //Transforme une chaine en camelCase (ou pascaleCase si deucième param à true)
    public static function toCalmeCase(string $value, $pascalCase = false): string
    {
        /* On remplacee les tiret et underscore par des espaces,
        puis on met les premières lettres de chaque mot en majuscule avec ucword */
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        //On retire les espaces
        $value = str_replace(' ', '', $value);
        //Si le param $pascalCase est true, on met la première lettre en minuscule
        if ($pascalCase === false) {
            return lcfirst($value);
        } else {
            return $value;
        }
    }

    //Transforme une chaine en pascalCase (lowerCamelCase) en appellant avec le deuxième param à true
    public static function toPascalCase(string $value): string
    {
        return self::toCalmeCase($value, true);
    }

    //Transforme une chaine pour le rendre valide pour les urls, nom de fichier
    public static function slugify($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
