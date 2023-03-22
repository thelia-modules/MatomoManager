<?php

namespace MatomoManager\Hook;

use MatomoManager\MatomoManager;
use MatomoManager\Service\Tracking\EventTracking\ProductTrackingService;
use MatomoManager\Service\Tracking\TrackingService;
use Propel\Runtime\Exception\PropelException;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\ProductQuery;

class FrontHook extends BaseHook
{
    protected $productTrackingService;
    protected $trackingService;

    public function __construct(
        TrackingService        $trackingService,
        ProductTrackingService $productTrackingService
    )
    {
        $this->productTrackingService = $productTrackingService;
        $this->trackingService = $trackingService;
    }

    public function injectTracker(HookRenderEvent $event): void
    {
        $action = MatomoManager::getConfigValue('matomo_configuration_mode');

        $event->add(
            $this->render(
                'tracker/' . $action . '.html',

                $this->trackingService->getTrackerTemplatePrameters($action)
            )
        );
    }

    public function injectConsent(HookRenderEvent $event): void
    {
        $event->add($this->render('consent/consent-modal.html'));
    }

    /**
     * @throws PropelException
     */
    public function injectProductTracker(HookRenderEvent $event): void
    {
        if (!MatomoManager::getConfigValue('matomo_ecommerce_track_product')) {
            return;
        }

        $productId = $event->getArgument('product');

        $product = ProductQuery::create()->findPk($productId);

        if (!$product) {
            return;
        }

        $this->productTrackingService->trackProduct($product);
    }

    public function onMainFooterBottom(HookRenderEvent $event)
    {
        $event->add($this->render('consent/remove-consent.html', [
            'CONSENT_TRACKING' => MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking')
        ]));
    }
}