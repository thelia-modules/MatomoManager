<?php

namespace MatomoManager\Form;

use MatomoManager\MatomoManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ConfigurationMode extends BaseForm
{
    protected function buildForm()
    {
        $form = $this->formBuilder;

        $matomomanagerConfigurationMode = MatomoManager::getConfigValue('matomo_configuration_mode');

        $form->add(
            'matomo_configuration_mode',
            ChoiceType::class,
            [
                'data' => $matomomanagerConfigurationMode,
                'choices' => [
                    Translator::getInstance()->trans('Use script', [], MatomoManager::DOMAIN_NAME) => 'integration_script',
                    Translator::getInstance()->trans('Easy analytics', [], MatomoManager::DOMAIN_NAME) => 'analytics' ,
                    Translator::getInstance()->trans('Easy TagManager', [], MatomoManager::DOMAIN_NAME) => 'tag_manager',
                ],
                'label' => Translator::getInstance()->trans('Matomo configuration mode', [], MatomoManager::DOMAIN_NAME),
                'label_attr' => [
                    'for' => 'matomo_configuration_mode'
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ]
        );
    }
}