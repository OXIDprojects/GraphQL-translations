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

    public function getTranslations(string $languageKey, int $shopId): array;

    public function getTranslationByKey(string $languageKey, string $key, int $shopId): Translation;

    public function updateTranslation(string $languageKey, Translation $translation, int $shopId): Translation;

    public function resetTranslationByKey(string $languageKey, string $key, int $shopId): Translation;

    public function resetTranslations(string $languageKey, int $shopId): bool;

}
