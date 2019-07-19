<?php
declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Type\ObjectType;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\GenericFieldResolverInterface;

class TranslationsType extends ObjectType
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
            'name'         => 'Category',
            'description'  => 'Rudimentary category object',
            'fields'       => [
                'id'       => Type::string(),
                'parentid' => Type::string(),
                'name'    => Type::string()
            ],
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return $this->genericFieldResolver->getField($info->fieldName, $value);
            }
        ];
        parent::__construct($config);
    }

}
