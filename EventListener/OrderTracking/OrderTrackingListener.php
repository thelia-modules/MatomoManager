<?php

namespace MatomoManager\EventListener\OrderTracking;

use MatomoManager\MatomoManager;
use MatomoManager\Service\Tracking\EventTracking\OrderTrackingService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Event\OrderStatusEvent;


class OrderTrackingListener implements EventSubscriberInterface
{
    public function __construct(
        protected OrderTrackingService $orderTrackingService,
        protected RequestStack $requestStack
    )
    {

    }

    /**
     * @param orderEvent $event
     */
    public function trackOrderPay(OrderEvent $event): void
    {
        if (!MatomoManager::getConfigValue('matomo_ecommerce_track_order')) {
            return;
        }

        $order = $event->getOrder();

        if ($order->isPaid(true)) {
            $this->orderTrackingService->trackOrderPaid($order);
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_UPDATE_STATUS => ['trackOrderPay', 100]
        ];
    }
}