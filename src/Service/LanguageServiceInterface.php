<?php

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\GraphQl\Translations\DataObject\Language;

interface LanguageServiceInterface
{
    public function getLanguages(): array;

    public function getLanguage(string $languageKey): Language;
}