<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQl\Translations\DataObject\Language;
use OxidEsales\GraphQl\Translations\Exception\LanguageNotFound;

class LanguageService implements LanguageServiceInterface
{

    public function getLanguages(): array
    {
        $defaultLanguageId = (int) Registry::getConfig()->getConfigParam('sDefaultLang');
        $oxlang = Registry::getLang();
        $languages = [];
        foreach ($oxlang->getLanguageArray() as $oxlanguage) {
            $language = new Language();
            $language->setLanguageKey($oxlanguage->abbr);
            $language->setLanguageName($oxlanguage->name);
            $language->setIsActive($oxlanguage->active == true);
            $language->setIsDefault($oxlanguage->id === $defaultLanguageId);
            
            $languages[] = $language;
        };
        return $languages;
    }

    public function getLanguage(string $languageKey): Language
    {
        /** @var Language $language */
        foreach ($this->getLanguages() as $language) {
            if ($language->getLanguageKey() === $languageKey) {
                return $language;
            }
        }
        throw new LanguageNotFound();
    }
}