<?php

namespace OxidEsales\GraphQl\Tests\Translations\Service;

use OxidEsales\GraphQl\Translations\DataObject\Locale;
use OxidEsales\GraphQl\Translations\Service\LocaleService;
use OxidEsales\GraphQl\Translations\Service\LocaleServiceInterface;
use PHPUnit\Framework\TestCase;

class LocaleServiceTest extends TestCase
{

    /** @var LocaleServiceInterface $localeService */
    private $localeService;

    public function setUp()
    {
        $this->localeService = new LocaleService();
    }

    public function testGetLocales()
    {
        $locales = $this->localeService->getLocales();
        $this->assertTrue(sizeof($locales) > 0, 'No locales found.');
        $this->assertInstanceOf(Locale::class, $locales[0]);
        $defaults = 0;
        /** @var Locale $locale */
        foreach ($locales as $locale) {
            if ($locale->getIsDefault()) {
                $defaults +=1;
            }
        }
        $this->assertEquals(1, $defaults);
    }

    public function testGetLocale()
    {
        $locale = $this->localeService->getLocale('en');
        $this->assertEquals('en', $locale->getLanguageKey());

    }
}
