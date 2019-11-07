<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Dao;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Translations\DataObject\Language;

class LanguageDao implements LanguageDaoInterface
{

    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getLanguages(int $shopId): array
    {
        $defaultLanguageId = (int) Registry::getConfig()->getConfigParam('sDefaultLang');
        $oxlang = Registry::getLang();
        $languages = [];
        foreach ($oxlang->getLanguageArray() as $oxlanguage) {
            $language = new Language(
                strval($oxlanguage->id),
                $oxlanguage->name,
                $oxlanguage->abbr,
                $oxlanguage->active == true,
                $oxlanguage->id === $defaultLanguageId
            );

            $languages[] = $language;
        };

        return $languages;
    }

    public function getLanguageById(string $id, int $shopId): ?Language
    {
        /** @var Language $language */
        foreach ($this->getLanguages($shopId) as $language) {
            if ($language->getId() === $id) {
                return $language;
            }
        }
        throw new LanguageNotFound();
    }
}
