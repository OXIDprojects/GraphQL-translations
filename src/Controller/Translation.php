<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Controller;

use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Translations\Dao\TranslationDaoInterface;
use OxidEsales\GraphQL\Translations\DataObject\Translation as TranslationDataObject;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;
use TheCodingMachine\GraphQLite\Types\ID;

class Translation
{
    /** @var TranslationDaoInterface */
    protected $translationDao;

    /** @var LegacyServiceInterface */
    private $legacyService = null;

    public function __construct(
        TranslationDaoInterface $translationDao,
        LegacyServiceInterface $legacyService
    ) {
        $this->translationDao = $translationDao;
        $this->legacyService = $legacyService;
    }

    /**
     * @Query()
     * @return TranslationDataObject[]
     */
    public function translations(ID $languageKey): array
    {
        return $this->translationDao->getTranslations(
            $languageKey->val(),
            $this->legacyService->getShopId()
        );
    }

    /**
     * @Query()
     */
    public function translation(ID $languageKey, ID $key): ?TranslationDataObject
    {
        return $this->translationDao->getTranslationByKey(
            $languageKey->val(),
            $key->val(),
            $this->legacyService->getShopId()
        );
    }

    /**
     * @Mutation()
     * @Logged()
     * @Right("TRANSLATION_UPDATE")
     */
    public function translationUpdate( ID $languageKey, TranslationDataObject $translation): TranslationDataObject
    {
        return $this->translationDao->updateTranslation(
            $languageKey->val(),
            $translation,
            $this->legacyService->getShopId()
        );
    }

    /**
     * @Mutation()
     * @Logged()
     * @Right("TRANSLATION_UPDATE")
     */
    public function translationReset(ID $languageKey, ID $key): bool
    {
        return $this->translationDao->resetTranslationByKey(
            $languageKey->val(),
            $key->val(),
            $this->legacyService->getShopId()
        );
    }

    /**
     * @Mutation()
     * @Logged()
     * @Right("TRANSLATION_UPDATE")
     */
    public function translationResetAll(ID $languageKey): bool
    {
        return $this->translationDao->resetTranslations(
            $languageKey->val(),
            $this->legacyService->getShopId()
        );
    }
}
