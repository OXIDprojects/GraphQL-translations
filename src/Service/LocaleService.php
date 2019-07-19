<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQl\Translations\DataObject\Locale;
use OxidEsales\GraphQl\Translations\Exception\LocaleNotFound;

class LocaleService implements LocaleServiceInterface
{

    public function getLocales(): array
    {
        $defaultLanguageId = (int) Registry::getConfig()->getConfigParam('sDefaultLang');
        $oxlang = Registry::getLang();
        $locales = [];
        foreach ($oxlang->getLanguageArray() as $language) {
            $locale = new Locale();
            $locale->setLanguageKey($language->abbr);
            $locale->setIsActive($language->active == true);
            $locale->setIsDefault($language->id === $defaultLanguageId);
            $locale->setName($language->name);
            $locales[] = $locale;
        };
        return $locales;
    }

    public function getLocale(string $languageKey): Locale
    {
        /** @var Locale $locale */
        foreach ($this->getLocales() as $locale) {
            if ($locale->getLanguageKey() === $languageKey) {
                return $locale;
            }
        }
        throw new LocaleNotFound();
    }
}