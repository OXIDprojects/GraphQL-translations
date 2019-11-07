<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Translations\DataObject;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\GraphQL\Translations\Dao\LanguageDaoInterface;
use TheCodingMachine\GraphQLite\Annotations\Factory;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
class Language
{

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $key;

    /** @var bool */
    private $isActive;

    /** @var bool */
    private $isDefault;

    public function __construct(
        string $id,
        string $name,
        string $key,
        bool $isActive,
        bool $isDefault
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->key = $key;
        $this->isActive = $isActive;
        $this->isDefault = $isDefault;
    }

    /**
     * @Field(outputType="ID")
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @Field()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @Field()
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @Field()
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @Field()
     */
    public function getIsDeafult(): bool
    {
        return $this->isDefault;
    }
}
