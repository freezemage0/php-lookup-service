<?php


namespace Freezemage\LookupBot\Documentation;

enum Language: string
{
    case CHINESE_SIMPLIFIED = 'zh';
    case GERMAN = 'de';
    case JAPANESE = 'ja';
    case SPANISH = 'es';
    case TURKISH = 'tr';
    case BRAZILIAN_PORTUGUESE = 'pt_BR';
    case ENGLISH = 'en';
    case FRENCH = 'fr';
    case RUSSIAN = 'ru';

    public static function match(string $value): ?Language
    {
        foreach (Language::cases() as $language) {
            if ($language->value === $value) {
                return $language;
            }
        }

        return null;
    }
}
