<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Translations\Type\Provider;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OxidEsales\GraphQl\Framework\AppContext;
use OxidEsales\GraphQl\Translations\Service\TranslationServiceInterface;
use OxidEsales\GraphQl\Translations\Type\ObjectType\LocaleType;
use OxidEsales\GraphQl\Service\PermissionsServiceInterface;
use OxidEsales\GraphQl\Translations\Type\ObjectType\TranslationType;
use OxidEsales\GraphQl\Type\Provider\MutationProviderInterface;
use OxidEsales\GraphQl\Type\Provider\QueryProviderInterface;

class TranslationProvider implements QueryProviderInterface, MutationProviderInterface
{

    /** @var  PermissionsServiceInterface */
    private $permissionsService;

    /** @var TranslationServiceInterface */
    private $translationService;

    /** @var  TranslationType */
    private $translationType;

    /** @var  LocaleType */
    private $localeType;

    public function __construct(
        PermissionsServiceInterface $permissionsService,
        TranslationServiceInterface $translationService,
        TranslationType $translationType
    ) {
        $this->permissionsService = $permissionsService;
        $this->translationService =$translationService;
        $this->translationType = $translationType;
    }

    public function getQueries()
    {
        return [
            'translations' => [
                'type'        => Type::listOf($this->translationType),
                'description' => 'Get a list of translations.',
                'args'        => [
                    'languageKey' => Type::nonNull(Type::id())
                ]
            ],
            'translation' => [
                'type'  => $this->translationType,
                'description' => 'Get a specific translation for a language',
                'args' => [
                    'languageKey' => Type::nonNull(Type::id()),
                    'translationKey' => Type::nonNull(Type::id())
                ]
            ]
        ];
    }

    public function getQueryResolvers()
    {
        return [
            'translations' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                return $this->translationService->getTranslations($args['languageKey']);
            },
            'translation' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'mayreaddata');
                return $this->translationService->getTranslation($args['languageKey'], $args['translationKey']);
            }
        ];
    }

    public function getMutations()
    {
        return [
            'updateTranslation' => [
                'type'        => $this->translationType,
                'description' => 'update translation object',
                'args'        => [
                    'languageKey' => Type::nonNull(Type::string()),
                    'translationKey' => Type::nonNull(Type::string()),
                    'translationValue' => Type::nonNull(Type::string())
                ]
            ],
            'resetTranslations' => [
                'type'        => Type::string(),
                'description' => 'Danger! Delete all changed translations for language.',
                'args'        => [
                    'languageKey' => Type::nonNull(Type::string())
                ]
            ]
        ];
    }

    public function getMutationResolvers()
    {
        return [
            'updateTranslation' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'maywritetranslation');

                return $this->translationService->updateTranslation($args['languageKey'], $args['translationKey'], $args['translationValue']);
            },
            'resetTranslations' => function ($value, $args, $context, ResolveInfo $info) {
                /** @var AppContext $context */
                $token = $context->getAuthToken();
                $this->permissionsService->checkPermission($token, 'maywritetranslation');
                $this->translationService->resetTranslations($args['languageKey']);

                return '';
            }
        ];
    }
}
