<?php

namespace MatomoManager\Service\Tracking\EventTracking;

use Exception;
use MatomoManager\MatomoManager;
use MatomoTracker;
use Propel\Runtime\Exception\PropelException;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Log\Tlog;
use Thelia\Model\CategoryQuery;
use Thelia\Model\Order;
use Thelia\Model\OrderProduct;
use Thelia\Model\ProductQuery;

class OrderTrackingService
{
    public function __construct(protected Session $session)
    {
    }

    public function trackOrderPaid(Order $order): void
    {
        try {
            $matomoTracker = MatomoManager::buildApiTracker();

            $tax = $itemsTax = 0;

            $amount = $order->getTotalAmount($tax);
            $itemsAmount = $order->getTotalAmount($itemsTax, false, false);

            $this->trackOrderProductItems($order, $matomoTracker);

            $matomoTracker->doTrackEcommerceOrder(
                $order->getId(),
                (float)$amount,
                (float)$itemsAmount,
                (float)$tax,
                (float)$order->getPostage(),
                (float)$order->getDiscount()
            );

            $matomoTracker->doTrackPageView('Order Pay');
        } catch (Exception $ex) {
            Tlog::getInstance()->addError(sprintf("Matomo order paid Tracker error : %s", $ex->getMessage()));
        }
    }

    /**
     * @throws PropelException | Exception
     */
    protected function trackOrderProductItems(Order $order, MatomoTracker $matomoTracker = null): void
    {
        $locale = $this->session->getLang()->getLocale();

        $matomoTracker = $matomoTracker ?? MatomoManager::buildApiTracker();

        $orderProducts = $order->getOrderProducts();

        /** @var OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            $product = ProductQuery::create()->filterByRef($orderProduct->getProductRef())->findOne();

            $category = null;

            if ($product) {
                $category = CategoryQuery::create()->findPk($product->getDefaultCategoryId());
                $category->setLocale($locale);
            }

            $matomoTracker->addEcommerceItem(
                $orderProduct->getProductRef(),
                $orderProduct->getTitle(),
                $category?->getTitle(),
                $orderProduct->getWasInPromo() ? (float)$orderProduct->getPromoPrice() : (float)$orderProduct->getPrice(),
                $orderProduct->getQuantity()
            );
        }
    }
}