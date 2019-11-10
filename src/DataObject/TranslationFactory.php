<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\DataObject;

use OxidEsales\EshopCommunity\Core\Registry;
use OxidEsales\GraphQL\Translations\DataObject\Translation;
use TheCodingMachine\GraphQLite\Annotations\Factory;
use TheCodingMachine\GraphQLite\Types\ID;

class TranslationFactory
{
    /**
     * @Factory()
     */
    public static function translationUpdate(
        ID $key,
        string $value
    ): Translation {
        return new Translation(
            $key->val(),
            $value
        );
    }
}