<?php

namespace MatomoManager\Form;

use MatomoManager\MatomoManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class TrackingEcommerce extends BaseForm
{
    protected function buildForm()
    {
        $form = $this->formBuilder;

        $tokernApi = MatomoManager::getConfigValue('matomo_token_api');
        $trackOrder = (bool) MatomoManager::getConfigValue('matomo_ecommerce_track_order');
        $trackCart = (bool) MatomoManager::getConfigValue('matomo_ecommerce_track_cart');
        $trackProduct = (bool) MatomoManager::getConfigValue('matomo_ecommerce_track_product');
        $trackSearch = (bool) MatomoManager::getConfigValue('matomo_ecommerce_track_search');

        $consentTracking = (bool) MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking');

        $form->add(
            'matomo_token_api',
            TextType::class,
            [
                'data' => $tokernApi,
                'label' => Translator::getInstance()->trans('Matomo token api', [], MatomoManager::DOMAIN_NAME),
                'label_attr' => [
                    'for' => 'matomo_api_token',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => true
            ]
        )
            ->add(
                'matomo_ecommerce_track_order',
                CheckboxType::class,
                [
                    'data' => $trackOrder,
                    'label' => Translator::getInstance()->trans('Track ecommerce order', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_ecommerce_track_order',
                    ],
                    'required' => false
                ]
            )
            ->add(
                'matomo_ecommerce_track_cart',
                CheckboxType::class,
                [
                    'data' => $trackCart,
                    'label' => Translator::getInstance()->trans('Track ecommerce cart', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_ecommerce_track_cart',
                    ],
                    'required' => false
                ]
            )
            ->add(
                'matomo_ecommerce_track_product',
                CheckboxType::class,
                [
                    'data' => $trackProduct,
                    'label' => Translator::getInstance()->trans('Track ecommerce category/product', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_ecommerce_track_product',
                    ],
                    'required' => false
                ]
            )
            ->add(
                'matomo_ecommerce_track_search',
                CheckboxType::class,
                [
                    'data' => $trackSearch,
                    'label' => Translator::getInstance()->trans('Track ecommerce search', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_ecommerce_track_search',
                    ],
                    'required' => false
                ]
            )
            ->add(
                'matomo_ecommerce_consent_tracking',
                CheckboxType::class,
                [
                    'data' => $consentTracking,
                    'label' => Translator::getInstance()->trans('Disable tracking if user doesn\'t consent', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_ecommerce_consent_tracking',
                    ],
                    'required' => false
                ]
            );
    }
}