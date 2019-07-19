<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Tests\Acceptance;

use OxidEsales\GraphQl\Framework\RequestReader;
use OxidEsales\GraphQl\Framework\RequestReaderInterface;
use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Sample\Dao\OxObjectCategoryDao;
use OxidEsales\GraphQl\Tests\Acceptance\BaseGraphQlAcceptanceTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OxObjectCategoryTest extends CategoryTest
{

    protected function beforeContainerCompile()
    {
        $definition = $this->container->getDefinition(CategoryDaoInterface::class);
        $definition->setClass(OxObjectCategoryDao::class);
    }

}
