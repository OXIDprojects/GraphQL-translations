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

class TranslationFactory
{
    /**
     * @Factory()
     */
    public static function updateTranslation(
        string $key,
        ?string $value = null
    ): Translation {
        return new Translation(
            $key,
            $value
        );
    }
}