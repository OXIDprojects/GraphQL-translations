<?php
declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Type\ObjectType;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\GenericFieldResolverInterface;

class LocaleType extends ObjectType
{

    /**
     * @var GenericFieldResolverInterface
     */
    private $genericFieldResolver;

    /**
     * UserType constructor.
     */
    public function __construct(GenericFieldResolverInterface $genericFieldResolver)
    {
        $this->genericFieldResolver = $genericFieldResolver;

        $config = [
            'name'         => 'Locale',
            'description'  => 'Locale existing in the shop',
            'fields'       => [
                'languageKey'       => Type::nonNull(Type::string()),
                'isActive' => Type::boolean(),
                'isDefault' => Type::boolean(),
                'name'    => Type::string()
            ],
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return $this->genericFieldResolver->getField($info->fieldName, $value);
            }
        ];
        parent::__construct($config);
    }

}
