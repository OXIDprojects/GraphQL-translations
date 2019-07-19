<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\DataObject;

class Translation
{
    /** @var string $languagekey */
    private $languagekey;
    /** @var  string $name */
    private $name;
    /** @var  string $value */
    private $value;

    /**
     * @return string
     */
    public function getLanguagekey(): string
    {
        return $this->languagekey;
    }

    /**
     * @param string $languagekey
     */
    public function setLanguagekey(string $languagekey): void
    {
        $this->languagekey = $languagekey;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }


}
