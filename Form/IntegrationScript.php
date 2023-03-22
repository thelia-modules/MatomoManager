<?php

namespace MatomoManager\Form;

use MatomoManager\MatomoManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class IntegrationScript extends BaseForm
{
    protected function buildForm()
    {
        $form = $this->formBuilder;

        $valueMatomoScript = MatomoManager::getConfigValue('matomo_integration_script');

        $form->add(
            'matomo_integration_script',
            TextareaType::class,
            [
                'data' => $valueMatomoScript,
                'label' => Translator::getInstance()->trans('Matomo integration script', [], MatomoManager::DOMAIN_NAME),
                'label_attr' => [
                    'for' => 'matomo_integration_script',
                ],
                'attr' => [
                    'rows' => '15'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => true
            ]
        );
    }
}