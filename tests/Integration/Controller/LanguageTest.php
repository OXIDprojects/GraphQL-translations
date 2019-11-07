<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class LanguageTest extends TestCase
{
    public function testGetSingleLanguageWithoutParam()
    {
        $this->execQuery('query { translation }');
        $this->assertEquals(
            400,
            static::$queryResult['status']
        );
    }

    public function testGetSingleLanguageWithNonExistantLanguageId()
    {
        $this->execQuery('query { translation (id: "does-not-exist"){id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['translation']
        );
    }

    public function testGetCategorieListWithoutParams()
    {
        $this->execQuery('query { translations {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
    }

    public function testCreateSimpleLanguage()
    {
        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);
        $this->execQuery('mutation { translationCreate(translation: {id: "10", name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['translationCreate']['name']
        );
    }

    /**
     * @depends testCreateSimpleLanguage
     */
    public function testGetSimpleLanguageJustCreatedById()
    {
        $this->execQuery('query { translation (id: "10") {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['translation']['name']
        );
    }

    /**
     * @depends testCreateSimpleLanguage
     */
    public function testGetSimpleLanguageJustCreated()
    {
        $this->execQuery('query { translations {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['translations'][0]['name']
        );
    }

    /**
     * @depends testCreateSimpleLanguage
     */
    public function testGetSimpleLanguageJustCreatedWithExtras()
    {
        $this->execQuery('query { translations {id, name, childs { id }, parent { id }}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['translations'][0]['name']
        );
        $this->assertEquals(
            [],
            static::$queryResult['body']['data']['translations'][0]['childs']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['translations'][0]['parent']
        );
    }
}
