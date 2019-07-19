<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Tests\Acceptance;

use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Tests\Acceptance\BaseGraphQlAcceptanceTestCase;

class CategoryTest extends BaseGraphQlAcceptanceTestCase
{

    use CategorySetupTrait;

    public function testGetCategoryEn()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1S1") {
        name
    }
}
EOQ;
        $token = $this->createToken('anonymous');
        $token->setLang('en');
        $token->setShopid(1);

        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatusOK();
        $this->assertEquals('subcategory 1-1', $this->queryResult['data']['category']['name']);

    }

    public function testGetCategoryDe()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1S1") {
        name
    }
}
EOQ;
        $token = $this->createToken('anonymous');
        $token->setLang('de');
        $token->setShopid(1);

        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatusOK();
        $this->assertEquals('Unterkategorie 1-1', $this->queryResult['data']['category']['name']);

    }

    public function testGetCategoryShop2()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1S1") {
        name
    }
}
EOQ;
        $token = $this->createToken('anonymous');
        $token->setLang('de');
        $token->setShopid(2);

        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatus(404);

    }

    public function testNotFound()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "nonexistingid") {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertHttpStatus(404);
        $this->assertErrorMessage('Category with id "nonexistingid" not found.');
        $this->assertLogMessageContains('Category with id "nonexistingid" not found.');
    }

    public function testGetRootCategories()
    {

        $query = <<<EOQ
query TestQuery { 
    categories {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertHttpStatusOK();
        $found = false;
        foreach ($this->queryResult['data']['categories'] as $categoryArray) {
            if ($categoryArray['name'] == 'Rootkategorie 1') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    public function testGetCategories()
    {

        $query = <<<EOQ
query TestQuery { 
    categories (parentid: "$this->rootIdS1") {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertHttpStatusOK();
        $this->assertEquals(2, sizeof($this->queryResult['data']['categories']));
    }

    public function testAddCategory()
    {

        $query = <<<EOQ
mutation TestMutation { 
    addCategory (names: ["Neue Kategorie", "New category"], parentid: "$this->rootIdS1")
}
EOQ;
        $token = $this->createToken('admin');
        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatusOK();
        $this->assertEquals(32, strlen($this->queryResult['data']['addCategory']));
    }

    public function testAddCategoryNoPermission()
    {

        $query = <<<EOQ
mutation TestMutation { 
    addCategory (names: ["Neue Kategorie", "New category"], parentid: "$this->rootIdS1")
}
EOQ;
        $token = $this->createToken('customer');
        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatus(403);
    }

}
