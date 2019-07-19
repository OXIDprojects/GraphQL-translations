<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Type\Provider;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\AppContext;
use OxidEsales\GraphQl\Translations\Type\ObjectType\LocaleType;
use OxidEsales\GraphQl\Service\PermissionsServiceInterface;
use OxidEsales\GraphQl\Translations\Type\ObjectType\TranslationType;
use OxidEsales\GraphQl\Type\Provider\MutationProviderInterface;
use OxidEsales\GraphQl\Type\Provider\QueryProviderInterface;

class TranslationProvider implements QueryProviderInterface, MutationProviderInterface
{
    /** @var  PermissionsServiceInterface */
    private $permissionsService;

    /** @var  TranslationType */
    private $translationType;

    /** @var  LocaleType */
    private $localeType;

    public function __construct(
        PermissionsServiceInterface $permissionsService,
        TranslationType $translationType,
        LocalType $localeType
    ) {
        $this->permissionsService = $permissionsService;
        $this->translationType = $translationType;
        $this->localeType = $localeType;
    }

    public function getQueries()
    {
        return ['
            translations' => [
                'type'        => Type::listOf($this->translationType),
                'description' => 'Get a list of translations.',
                'args'        => [
                    'languagekey' => Type::nonNull(Type::string())
                ]
            ],
        ];
    }

    public function getQueryResolvers()
    {
        return [
            'translations' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');

                return [];
            }
        ];
    }

    public function getMutations()
    {
        return [];
    }

    public function getMutationResolvers()
    {
        return [];
    }
}
