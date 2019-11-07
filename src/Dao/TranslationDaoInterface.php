<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Dao;

use OxidEsales\GraphQL\Translations\DataObject\Translation;

interface TranslationDaoInterface
{
    public function getTranslations(string $languageId, int $shopId): array;

    public function getTranslationByKey(string $languageId, string $key, int $shopId): Translation;


    /**
     * @return Translation[]
     */
    //public function createTranslation(Translation $language, int $shopId): Translation;
}
