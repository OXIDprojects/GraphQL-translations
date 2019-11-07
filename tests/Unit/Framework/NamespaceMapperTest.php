<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Tests\Unit\Framework;

use PHPUnit\Framework\TestCase;
# use OxidEsales\TestingLibrary\UnitTestCase as TestCase;
use OxidEsales\GraphQL\Translations\Framework\NamespaceMapper;

class NamespaceMapperTest extends TestCase
{

    /**
     * @covers OxidEsales\GraphQL\Translations\Framework\NamespaceMapper
     */
    public function testFooBar()
    {
        $namespaceMapper = new NamespaceMapper();
        $this->assertCount(
            1,
            $namespaceMapper->getControllerNamespaceMapping()
        );
        $this->assertCount(
            1,
            $namespaceMapper->getTypeNamespaceMapping()
        );
    }
}
