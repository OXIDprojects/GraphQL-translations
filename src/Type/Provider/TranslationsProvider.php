<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Type\Provider;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\AppContext;
use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Sample\Type\ObjectType\TranslationsType;
use OxidEsales\GraphQl\Service\PermissionsServiceInterface;
use OxidEsales\GraphQl\Type\Provider\MutationProviderInterface;
use OxidEsales\GraphQl\Type\Provider\QueryProviderInterface;

class TranslationsProvider implements QueryProviderInterface, MutationProviderInterface
{
    /** @var  CategoryDaoInterface */
    private $categoryDao;

    /** @var  PermissionsServiceInterface */
    private $permissionsService;

    /** @var  TranslationsType */
    private $categoryType;

    public function __construct(CategoryDaoInterface $categoryDao,
                                PermissionsServiceInterface $permissionsService,
                                TranslationsType $categoryType)
    {
        $this->categoryDao = $categoryDao;
        $this->permissionsService = $permissionsService;
        $this->categoryType = $categoryType;
    }

    public function getQueries()
    {
        return [
            'category' => [
                'type'        => $this->categoryType,
                'description' => 'Get a category object.',
                'args'        => [
                    'categoryid' => Type::nonNull(Type::string())
                ]
            ],
            'categories' => [
                'type'        => Type::listOf($this->categoryType),
                'description' => 'Get a list of child objects for a parent category. ' .
                                 'If no parentid is given, the root categories are returned.',
                'args'        => [
                    'parentid' => Type::string()
                ]
            ]
        ];
    }

    public function getQueryResolvers()
    {
        return [
            'category' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                return $this->categoryDao->getCategory(
                    $args['categoryid'],
                    $token->getLang(),
                    $token->getShopid()
                );
            },
            'categories' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                if (array_key_exists('parentid', $args)) {
                    return $this->categoryDao->getCategories($token->getLang(), $token->getShopid(), $args['parentid']);
                }
                else {
                    return $this->categoryDao->getCategories($token->getLang(), $token->getShopid());
                }
            }
        ];
    }

    public function getMutations()
    {
        return [
            'addCategory' => [
                'type'        => Type::string(),
                'description' => 'Add a new category object.',
                'args'        => [
                    'names' => Type::nonNull(Type::listOf(Type::string())),
                    'parentid' => Type::string()
                ]
            ]
        ];
    }

    public function getMutationResolvers()
    {
        return [
            'addCategory' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayaddcategory');
                if (array_key_exists('parentid', $args)) {
                    return $this->categoryDao->addCategory($args['names'], $token->getShopid(), $args['parentid']);
                }
                else {
                    return $this->categoryDao->addCategory($args['names'], $token->getShopid());
                }
            }
        ];
    }

}
