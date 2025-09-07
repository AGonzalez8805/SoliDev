<?php

namespace App\Models;

class StringTools
{
    // Transforme une chaîne en camelCase (ou pascalCase si deuxième param à true)
    public static function toCalmeCase(string $value, bool $pascalCase = false): string
    {
        // Remplace tirets et underscores par des espaces, puis met en majuscule les premières lettres
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        // Retire les espaces
        $value = str_replace(' ', '', $value);
        // CamelCase ou PascalCase
        return $pascalCase ? $value : lcfirst($value);
    }

    // Transforme une chaîne en PascalCase (alias lowerCamelCase)
    public static function toPascalCase(string $value): string
    {
        return self::toCalmeCase($value, true);
    }

    // Transforme une chaîne pour le rendre valide pour les URLs ou noms de fichiers
    public static function slugify(string $text, string $divider = '-'): string
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        return strtolower($text) ?: 'n-a';
    }

    // Décode les entités HTML pour un affichage correct (ex: l&#039;instant → l'instant)
    public static function decodeHtmlEntities(string $text): string
    {
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // Optionnel : nettoie une chaîne pour l'affichage (décodage + nl2br)
    public static function formatForDisplay(string $text): string
    {
        return nl2br(self::decodeHtmlEntities($text));
    }
}
