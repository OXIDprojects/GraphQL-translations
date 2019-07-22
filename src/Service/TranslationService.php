<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Language;
use OxidEsales\Facts\Facts;
use OxidEsales\GraphQl\Translations\DataObject\Translation;
use OxidEsales\GraphQl\Translations\Exception\LocaleNotFound;
use OxidEsales\GraphQl\Translations\Exception\TranslationKeyNotFound;
use Webmozart\PathUtil\Path;

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
            $translation->setLanguageKey($languageKey);
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
     * @throws LocaleNotFound
     * @throws TranslationKeyNotFound
     */
    public function updateTranslation(string $languageKey, string $translationKey, string $translationValue): Translation
    {
        $config = $this->getConfig();
        $languages = $config->getConfigParam('aLanguages');

        $langName = $languages[$languageKey];

        // we need to keep the array name ($aLang) from the file in order to merge with the last changes
        $aLang[$translationKey] = $translationValue;

        $translationFile = $this->getTranslationFile($languageKey);

        if (file_exists($translationFile) && is_readable($translationFile)) {
            include $translationFile;
        }

        $aLang  = array_merge(['charset' => 'UTF-8'], $aLang );
        $aLang [$translationKey] = $translationValue;
        $content = '<?php

$sLangName  = "'. $langName .'";

$aLang = ';

        // Append a new prettified array to the file
        $content .= $this->_prettyPrintArray($aLang) . ';';

        // Write the contents back to the file
        if (file_put_contents($translationFile, $content)){
            $translation =  $this->getTranslation( $languageKey, $translationKey);
            $this->deleteCaches();
            return $translation;
        }

        throw new TranslationKeyNotFound();
    }

    public function resetTranslations(string $languageKey): void
    {
        $translationFile = $this->getTranslationFile($languageKey);
        if (file_exists($translationFile)) {
            unlink($translationFile);
        }
        $this->deleteCaches();
    }

    private function getTranslationFile(string $languageKey): string
    {
        return Path::join($this->getConfig()->getAppDir(), 'translations', $languageKey, 'particular_lang.php');
    }

    private function deleteCaches()
    {
        // Do it quick and dirty
        $facts = new Facts();
        $tmpDir = Path::join($facts->getSourcePath(), 'tmp');
        shell_exec("rm $tmpDir/*langcache*.txt 2>&1 ");

        $classCache = new \ReflectionProperty(Language::class, '_aLangCache');
        $classCache->setAccessible(true);
        $classCache->setValue(Registry::getLang(), []);
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