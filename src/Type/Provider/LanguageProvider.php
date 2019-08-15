<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Type\Provider;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\AppContext;
use OxidEsales\GraphQl\Translations\Service\LanguageServiceInterface;
use OxidEsales\GraphQl\Translations\Type\ObjectType\LanguageType;
use OxidEsales\GraphQl\Service\PermissionsServiceInterface;
use OxidEsales\GraphQl\Type\Provider\QueryProviderInterface;

class LanguageProvider implements QueryProviderInterface
{
    /** @var LanguageServiceInterface $languageService */
    private $languageService;

    /** @var  PermissionsServiceInterface */
    private $permissionsService;

    /** @var  LanguageType */
    private $languageType;

    public function __construct(
        LanguageServiceInterface $languageService,
        PermissionsServiceInterface $permissionsService,
        LanguageType $languageType
    ) {
        $this->languageService = $languageService;
        $this->permissionsService = $permissionsService;
        $this->languageType = $languageType;
    }

    public function getQueries()
    {
        return [
            'language' => [
                'type' => $this->languageType,
                'description' => 'Get a specific shop language object.',
                'args' => [
                    'languageKey' => Type::nonNull(Type::id())
                ]
            ],
            'languages' => [
                'type' => Type::listOf($this->languageType),
                'description' => 'Get a list of languages.'
            ]
        ];
    }

    public function getQueryResolvers()
    {
        return [
            'language' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                $languageKey = $args['languageKey'];
                return $this->languageService->getLanguage($languageKey);
            },
            'languages' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                return $this->languageService->getLanguages();
            }
        ];
    }

}
