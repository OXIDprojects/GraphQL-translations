<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            '\\OxidEsales\\GraphQL\\Translations\\Controller' => __DIR__ . '/../Controller/'
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            '\\OxidEsales\\GraphQL\\Translations\\DataObject' => __DIR__ . '/../DataObject/'
        ];
    }
}
