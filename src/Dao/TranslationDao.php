<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Dao;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Language;
use OxidEsales\Facts\Facts;
use OxidEsales\GraphQL\Translations\DataObject\Translation;
use Webmozart\PathUtil\Path;

class TranslationDao implements TranslationDaoInterface
{

    /** @var \ReflectionMethod $fetchMethod */
    private $fetchMethod;

    public function __construct() {
        $this->fetchMethod = new \ReflectionMethod(Language::class, '_getLangTranslationArray');
        $this->fetchMethod->setAccessible(true);    
    }

    /**
     * 
     */
    public function getTranslations(string $languageId, int $shopId): array
    {
        $translations = [];
       
        $oxLang = Registry::getLang();
        $oxTranslations = $this->fetchMethod->invoke($oxLang, $languageId);

        foreach ($oxTranslations as $key => $value) {
            if (strtoupper($key) !== $key) {
                continue;
            }
            $translation = new Translation(
                $key,
                $value
            );
           
            $translations[] = $translation;
        }

        return $translations;
    }

    /**
     * 
     */
    public function getTranslationByKey(string $languageId, string $key, int $shopId): Translation
    {
        foreach ($this->getTranslations($languageId, $shopId) as $translation) {
            if ($translation->getKey() === strtoupper($key)) {
                return $translation;
            }
        }
    }

    /**
     * @return Translation
     * @throws LanguageNotFound
     * @throws TranslationKeyNotFound
     */
    public function updateTranslation(Translation $translation, string $languageKey, int $shopKey): Translation
    {
        $config = $this->getConfig();
        $languages = $config->getConfigParam('aLanguages');

        $langName = $languages[$languageKey];
        $key = $translation->getKey();
        $value = $translation->getValue();

        // we need to keep the file variable array name ($aLang), in order to merge with the last changes
        $aLang[$key] = $value;

        $translationFile = $this->getTranslationFile($languageKey);

        if (file_exists($translationFile) && is_readable($translationFile)) {
            include $translationFile;
        }

        $aLang  = array_merge(['charset' => 'UTF-8'], $aLang );
        $aLang [$key] = $value;
        
        $this->writeTranslation($aLang, $translationFile);
        return $translation;
    }

    /**
     * Get Oxid config
     * 
     * @return mixed
     */
    protected function getConfig()
    {
        $config = Registry::getConfig();
        return $config;
    }

    /**
     * Get the translation file
     */
    private function getTranslationFile(string $languageKey): string
    {
        return Path::join($this->getConfig()->getAppDir(), 'translations', $languageKey, 'extra_lang.php');
    }

    private function writeTranslation(array $aLang,  string $translationFile): bool
    {
        $content = '<?php

$sLangName  = "'. $langName .'";

$aLang = ';

        // Append a new prettified array to the file
        $content .= $this->_prettyPrintArray($aLang) . ';';

        // Write the contents back to the file
        if (file_put_contents($translationFile, $content)){
            if($this->deleteCaches()){
                return true;
            }
            return false;
        }

        throw new TranslationKeyNotFound();    
    }

    /**
     * Delete oxid lang cache
     */
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
     * Write array separated by tabs
     * 
     * @param $arr
     * @param int $pad
     * @param string $padStr
     * @return string
     */
    protected function _prettyPrintArray($arr, $pad = 0, $padStr = "\t", $padArrow = 14): string
    {
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

    /**
     * Reset translation by tranlsation key to the default value
     */
    public function resetTranslationByKey(string $languageKey, string $key, int $shopId): bool
    {
        $translationFile = $this->getTranslationFile($languageKey);
        if (file_exists($translationFile)) {
            include $translationFile;
        }

        unset( $aLang [$key] );

        return $this->writeTranslation($aLang,  $translationFile);
    }

    /**
     * Reset all custom translations
     */
    public function resetTranslations(string $languageKey, int $shopId): bool
    {
        $translationFile = $this->getTranslationFile($languageKey);
        if (file_exists($translationFile)) {
            unlink($translationFile);
        }
    
        if($this->deleteCaches()){
            return true;
        }
        return false;
    }
}
