<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Language;
use OxidEsales\GraphQl\Translations\DataObject\Translation;
use OxidEsales\GraphQl\Translations\Exception\LocaleNotFound;
use OxidEsales\GraphQl\Translations\Exception\TranslationKeyNotFound;

class TranslationService implements TranslationServiceInterface
{
    /** @var \ReflectionMethod $fetchMethod */
    private $fetchMethod;

    public function __construct()
    {
        $this->fetchMethod = new \ReflectionMethod(Language::class, '_getLangTranslationArray');
        $this->fetchMethod->setAccessible(true);
    }

    /**
     * @param string $languageKey
     * @return array
     * @throws LocaleNotFound
     */
    public function getTranslations(string $languageKey): array
    {
        $oxLang = Registry::getLang();
        $oxTranslations = $this->fetchMethod->invoke($oxLang, $this->getLanguageId($languageKey));

        $translations = [];
        foreach ($oxTranslations as $key => $value) {
            if (strtoupper($key) !== $key) {
                continue;
            }
            $translation = new Translation();
            $translation->setName($key);
            $translation->setValue($value);
            $translation->setLanguagekey($languageKey);
            $translations[] = $translation;
        }
        return $translations;
    }

    /**
     * @param string $languageKey
     * @return int
     * @throws LocaleNotFound
     */
    private function getLanguageId(string $languageKey): int
    {
        $oxlang = Registry::getLang();
        foreach ($oxlang->getLanguageArray() as $lang) {
            if ($lang->abbr === $languageKey) {
                return $lang->id;
            }
        }
        throw new LocaleNotFound();
    }

    /**
     * @param string $languageKey
     * @param string $translationKey
     * @return Translation
     * @throws LocaleNotFound
     * @throws TranslationKeyNotFound
     */
    public function getTranslation(string $languageKey, string $translationKey): Translation
    {
        foreach ($this->getTranslations($languageKey) as $translation) {
            if ($translation->getName() === strtoupper($translationKey)) {
                return $translation;
            }
        }
        throw new TranslationKeyNotFound();
    }
}