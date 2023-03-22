<?php

namespace MatomoManager\Form;

use MatomoManager\MatomoManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class TagManager extends BaseForm
{
    protected function buildForm()
    {
        $matomomanagerContainer = MatomoManager::getConfigValue('matomo_tag_manager_container');
        $matomomanagerEnv = MatomoManager::getConfigValue('matomo_tag_manager_env', 'live');
        $valueMatomoUrl = MatomoManager::getConfigValue('matomo_url');

        $this->formBuilder
            ->add(
                'matomo_tag_manager_env',
                ChoiceType::class,
                [
                    'data' => $matomomanagerEnv,
                    'choices' => [
                        'Live' => 0,
                        'Dev' => 1,
                        'Staging' => 2
                    ],
                    'label' => Translator::getInstance()->trans('Matomo tag manager environment', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_tag_manager_env'
                    ],
                ]
            )
            ->add(
                'matomo_tag_manager_container',
                TextType::class,
                [
                    'data' => $matomomanagerContainer,
                    'label' => Translator::getInstance()->trans('Matomo tag manager container', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_tag_manager_container',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'required' => true
                ]
            )
            ->add(
                'matomo_url',
                TextType::class,
                [
                    'data' => $valueMatomoUrl,
                    'label' => Translator::getInstance()->trans('Matomo server base Url', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_url',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'required' => true
                ]
            );;
    }
}