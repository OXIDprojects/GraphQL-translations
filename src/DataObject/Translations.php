<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\DataObject;

class Translations
{
    /** @var  string $name */
    private $name;
    /** @var  string $id */
    private $id;
    /** @var  string|null $parentid */
    private $parentid;

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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * @param null|string $parentid
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;
    }

}
