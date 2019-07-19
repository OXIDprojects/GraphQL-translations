<?php

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\GraphQl\Translations\DataObject\Locale;

interface LocaleServiceInterface
{
    public function getLocales(): array;

    public function getLocale(string $languageKey): Locale;
}