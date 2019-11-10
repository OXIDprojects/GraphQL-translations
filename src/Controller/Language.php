<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Translations\Dao\LanguageDaoInterface;
use OxidEsales\GraphQL\Translations\DataObject\Language as LanguageDataObject;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;
use TheCodingMachine\GraphQLite\Types\ID;

class Language
{
    /** @var LanguageDaoInterface */
    protected $languageDao;

    /** @var LegacyServiceInterface */
    private $legacyService = null;

    public function __construct(
        LanguageDaoInterface $languageDao,
        LegacyServiceInterface $legacyService
    ) {
        $this->languageDao = $languageDao;
        $this->legacyService = $legacyService;
    }

    /**
     * Retrun a language object by ID
     * 
     * @Query()
     */
    public function language(ID $id): ?LanguageDataObject
    {
        return $this->languageDao->getLanguageById(
            $id->val(),
            $this->legacyService->getShopId()
        );
    }

    /**
     * Returns all the languages available in the shop
     * 
     * @Query()
     * @return LanguageDataObject[]
     */
    public function languages(): array
    {
        return $this->languageDao->getLanguages(
            $this->legacyService->getShopId()
        );
    }

    // /**
    //  * @Mutation()
    //  * @Logged()
    //  * @Right("LANGUAGE_CREATE")
    //  */
    // public function languageCreate(LanguageDataObject $language): LanguageDataObject
    // {
    //     return $this->languageDao->createLanguage(
    //         $language,
    //         $this->legacyService->getShopId()
    //     );
    // }
}
