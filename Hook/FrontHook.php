<?php

namespace MatomoManager\Hook;

use Exception;
use MatomoManager\MatomoManager;
use MatomoManager\Service\Tracking\EventTracking\ProductTrackingService;
use MatomoManager\Service\Tracking\TrackingService;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Log\Tlog;
use Thelia\Model\ProductQuery;

class FrontHook extends BaseHook
{
    public function __construct(
        protected TrackingService        $trackingService,
        protected ProductTrackingService $productTrackingService
    )
    {
    }

    public function injectTracker(HookRenderEvent $event): void
    {
        if (!$action = MatomoManager::getConfigValue('matomo_configuration_mode')) {
            return;
        }

        try {
            $trackerTemplateParameters = $this->trackingService->getTrackerTemplateParameters($action);

            $event->add(
                $this->render('tracker/' . $action . '.html',$trackerTemplateParameters)
            );

        } catch (Exception $ex) {
            Tlog::getInstance()->addError("Matomo configuration error : " . $ex->getMessage());
        }
    }

    public function injectConsent(HookRenderEvent $event): void
    {
        $event->add($this->render('consent/consent-modal.html',
            [
                "needCheckConsent" =>
                    MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking')
                    &&
                    MatomoManager::getConfigValue('matomo_ecommerce_track_product'),

                'CONSENT_TRACKING' => MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking')
            ]
        ));
    }

    public function injectProductTracker(HookRenderEvent $event): void
    {
        try {
            if (!MatomoManager::getConfigValue('matomo_ecommerce_track_product')) {
                throw new Exception("Configuration ecommerce not found.");
            }

            $productId = $event->getArgument('product');

            $product = ProductQuery::create()->findPk($productId);

            if (!$product) {
                throw new Exception("Product not found.");
            }

            $this->productTrackingService->trackProduct($product);

        } catch (\Exception $ex) {
            Tlog::getInstance()->addError(sprintf("Matomo product Tracker error : %s", $ex->getMessage()));
        }
    }

    public function onMainFooterBottom(HookRenderEvent $event): void
    {
        $event->add($this->render('consent/remove-consent.html', [
            'CONSENT_TRACKING' => MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking')
        ]));
    }
}