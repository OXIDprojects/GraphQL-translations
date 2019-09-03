<?php

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\GraphQl\Translations\DataObject\Translation;

interface TranslationServiceInterface
{
    public function getTranslations(string $languageKey): array;

    public function getTranslation(string $languageKey, string $translationKey): Translation;

    public function updateTranslation(string $languageKey, string $translationKey, string $translationValue): Translation;

    public function resetTranslation(string $languageKey,  string $translationKey): Translation;

    public function resetTranslations(string $languageKey): void;
}