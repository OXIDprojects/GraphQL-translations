<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\DataObject;

class Language
{
    private $languageKey;
    private $languageName;
    private $isActive;
    private $isDefault;
  
    /**
     * @return mixed
     */
    public function getLanguageKey()
    {
        return $this->languageKey;
    }

    /**
     * @param mixed $languageKey
     */
    public function setLanguageKey($languageKey): void
    {
        $this->languageKey = $languageKey;
    }

        /**
     * @return mixed
     */
    public function getLanguageName()
    {
        return $this->languageName;
    }

    /**
     * @param mixed $languageName
     */
    public function setLanguageName($languageName): void
    {
        $this->languageName = $languageName;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param mixed $isDefault
     */
    public function setIsDefault($isDefault): void
    {
        $this->isDefault = $isDefault;
    }
}