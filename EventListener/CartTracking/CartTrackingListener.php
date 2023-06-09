<?php

namespace MatomoManager\EventListener\CartTracking;

use MatomoManager\MatomoManager;
use MatomoManager\Service\Tracking\EventTracking\CartTrackingService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\TheliaEvents;

class CartTrackingListener implements EventSubscriberInterface
{
    public function __construct(
        protected CartTrackingService $cartTrackingService,
        protected RequestStack        $requestStack
    )
    {

    }

    public function trackCart(CartEvent $event): void
    {
        if (!MatomoManager::getConfigValue('matomo_ecommerce_track_cart')) {
            return;
        }
        if ($cart = $event->getCart()) {
            $this->cartTrackingService->trackCart($cart);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::CART_ADDITEM => ['trackCart', 100],
            TheliaEvents::CART_DELETEITEM => ['trackCart', 100],
            TheliaEvents::CART_UPDATEITEM => ['trackCart', 100],
        ];
    }
}