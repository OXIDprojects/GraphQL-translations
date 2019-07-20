<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\DataObject;

class Translation
{
    /** @var string $languageKey */
    private $languageKey;
    /** @var  string $translationKey */
    private $translationKey;
    /** @var  string $translationValue */
    private $translationValue;

    /**
     * @return string
     */
    public function getLanguageKey(): string
    {
        return $this->languageKey;
    }

    /**
     * @param string $languageKey
     */
    public function setLanguageKey(string $languageKey): void
    {
        $this->languageKey = $languageKey;
    }

    /**
     * @return string
     */
    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    /**
     * @param string $translationKey
     */
    public function setTranslationKey(string $translationKey): void
    {
        $this->translationKey = $translationKey;
    }

    /**
     * @return string
     */
    public function getTranslationValue(): string
    {
        return $this->translationValue;
    }

    /**
     * @param string $translationValue
     */
    public function setTranslationValue(string $translationValue): void
    {
        $this->translationValue = $translationValue;
    }


}
