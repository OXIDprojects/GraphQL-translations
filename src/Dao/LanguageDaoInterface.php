<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Dao;

use OxidEsales\GraphQL\Translations\DataObject\Language;

interface LanguageDaoInterface
{
    public function getLanguageById(string $id, int $shopId): ?Language;

    public function getLanguages(int $shopId): array;

    /**
     * @return Language[]
     */
    //public function createLanguage(Language $language, int $shopId): Language;
}
