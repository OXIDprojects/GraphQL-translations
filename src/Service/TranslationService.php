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
            $translation->setTranslationKey($key);
            $translation->setTranslationValue($value);
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
            if ($translation->getTranslationKey() === strtoupper($translationKey)) {
                return $translation;
            }
        }
        throw new TranslationKeyNotFound();
    }

    /**
     * @param string $languageKey
     * @param string $translationKey
     * @param string $translationValue
     * @return Translation
     */
    public function updateTranslation(string $languageKey, string $translationKey, string $translationValue): Translation
    {
        $config = $this->getConfig();
        $appDir = $config->getAppDir();
        $languages = $config->getConfigParam('aLanguages');

        $sLangName = $languages[$languageKey];
        $aLang[$translationKey] = $translationValue;

        // shop custom languages
        $file = $appDir . 'translations/' . $languageKey . '/cust_lang.php';

        if (file_exists($file) && is_readable($file)) {
            include $file;
        }

        $aLang = array_merge(['charset' => 'UTF-8'], $aLang);
        $aLang[$translationKey] = $translationValue;
        $content = '<?php

$sLangName  = "'. $sLangName .'";

$aLang = ';

        // Append a new prettified array to the file
        $content .= $this->_prettyPrintArray($aLang) . ';';

        // Write the contents back to the file
        if (file_put_contents($file, $content)){
            return $this->getTranslation( $languageKey, $translationKey);
        }

        throw new TranslationKeyNotFound();
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        $config = Registry::getConfig();
        return $config;
    }

    /**
     * @param $arr
     * @param int $pad
     * @param string $padStr
     * @return string
     */
    protected function _prettyPrintArray($arr, $pad = 0, $padStr = "\t", $padArrow = 14) {
        $outerPad = $pad;
        $innerPad = $pad + 1;
        $output = '[' . PHP_EOL;
        foreach ($arr as $k => $v) {
            $linePad =  $padArrow - round(strlen($k) / 4);
            $output .= str_repeat($padStr, $innerPad) . '"' . $k . '"' . str_repeat($padStr, (int)$linePad ) . '=> ' . '"' . $v . '",';
            $output .= PHP_EOL;
        }
        $output .= str_repeat($padStr, $outerPad) . ']';
        return $output;
    }
}