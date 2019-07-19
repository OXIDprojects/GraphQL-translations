<?php

namespace OxidEsales\GraphQl\Translations\Tests\Integration\Service;

use OxidEsales\GraphQl\Translations\DataObject\Translation;
use OxidEsales\GraphQl\Translations\Service\TranslationService;
use OxidEsales\GraphQl\Translations\Service\TranslationServiceInterface;
use PHPUnit\Framework\TestCase;

class TranslationServiceTest extends TestCase
{
    /** @var TranslationServiceInterface */
    private $translationService;

    public function setUp()
    {
        $this->translationService = new TranslationService();
    }
    public function testGetTranslations()
    {
        $translations = $this->translationService->getTranslations('en');
        $this->assertTrue(sizeof($translations) > 0, "Did not find any translations");
        $translation = $translations[0];
        $this->assertInstanceOf(Translation::class, $translation);
    }

    public function testGetTranslation()
    {
        $translation = $this->translationService->getTranslation('de', 'HELP');
        $this->assertEquals('Hilfe', $translation->getValue());
    }
}
