<?php

namespace MatomoManager\Form;

use MatomoManager\MatomoManager;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class Analytics extends BaseForm
{
    protected function buildForm(): void
    {
        $form = $this->formBuilder;

        $valueMatomoId = MatomoManager::getConfigValue('matomo_site_id');
        $valueMatomoUrl = MatomoManager::getConfigValue('matomo_url');

        $form->add(
                'matomo_site_id',
                NumberType::class,
                [
                    'data' => $valueMatomoId,
                    'label' => Translator::getInstance()->trans('Matomo Site Id', [], MatomoManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'matomo_site_id',
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
            );
    }
}