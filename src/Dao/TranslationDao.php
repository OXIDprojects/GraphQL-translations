<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Dao;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Language;
use OxidEsales\GraphQL\Translations\DataObject\Translation;

class TranslationDao implements TranslationDaoInterface
{

    /** @var \ReflectionMethod $fetchMethod */
    private $fetchMethod;

    public function __construct() {
        $this->fetchMethod = new \ReflectionMethod(Language::class, '_getLangTranslationArray');
        $this->fetchMethod->setAccessible(true);    
    }

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


    public function getTranslationByKey(string $languageId, string $key, int $shopId): Translation
    {
        foreach ($this->getTranslations($languageId, $shopId) as $translation) {
            if ($translation->getKey() === strtoupper($key)) {
                return $translation;
            }
        }
    }
}
