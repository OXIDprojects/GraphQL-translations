<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Type\Provider;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\AppContext;
use OxidEsales\GraphQl\Translations\Service\LocaleServiceInterface;
use OxidEsales\GraphQl\Translations\Type\ObjectType\LocaleType;
use OxidEsales\GraphQl\Service\PermissionsServiceInterface;
use OxidEsales\GraphQl\Type\Provider\QueryProviderInterface;

class LocaleProvider implements QueryProviderInterface
{
    /** @var LocaleServiceInterface $localeService */
    private $localeService;

    /** @var  PermissionsServiceInterface */
    private $permissionsService;

    /** @var  LocaleType */
    private $localeType;

    public function __construct(
        LocaleServiceInterface $localeService,
        PermissionsServiceInterface $permissionsService,
        LocaleType $localeType
    ) {
        $this->localeService = $localeService;
        $this->permissionsService = $permissionsService;
        $this->localeType = $localeType;
    }

    public function getQueries()
    {
        return [
            'locale' => [
                'type' => $this->localeType,
                'description' => 'Get a locale object.',
                'args' => [
                    'languageKey' => Type::nonNull(Type::string())
                ]
            ],
            'locales' => [
                'type' => Type::listOf($this->localeType),
                'description' => 'Get a list of locales.'
            ]
        ];
    }

    public function getQueryResolvers()
    {
        return [
            'locale' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                $languageKey = $args['languageKey'];
                return $this->localeService->getLocale($languageKey);
            },
            'locales' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                return $this->localeService->getLocales();
            }
        ];
    }

}
