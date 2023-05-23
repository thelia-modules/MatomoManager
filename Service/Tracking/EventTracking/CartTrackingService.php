<?php

namespace MatomoManager\Service\Tracking\EventTracking;

use Exception;
use MatomoManager\MatomoManager;
use MatomoTracker;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Log\Tlog;
use Thelia\Model\Base\CategoryQuery;
use Thelia\Model\Base\CountryQuery;
use Thelia\Model\Cart;
use Thelia\Model\CartItem;
use Thelia\Model\Customer;

class CartTrackingService
{
    /**
     * @param Session $session
     */
    public function __construct(protected Session $session)
    {
    }

    public function trackCart(Cart $cart): void
    {
        try {
            $matomoTracker = MatomoManager::buildApiTracker();
            $matomoTracker->doTrackEcommerceCartUpdate($cart->getTotalAmount());
        } catch (Exception $ex) {
            Tlog::getInstance()->addError(sprintf("Matomo cart Tracker error : %s", $ex->getMessage()));
        }
    }

    public function trackCartItem(Cart $cart, MatomoTracker $matomoTracker = null): void
    {
        try {
            $matomoTracker = $matomoTracker ?? MatomoManager::buildApiTracker();

            $locale = $this->session->getLang()->getLocale();
            /** @var Customer $customer */
            $customer = $this->session->getCustomerUser();

            $cartItems = $cart->getCartItems();

            /** @var CartItem $cartItem */
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->getProduct();
                $category = $category = null;

                if ($product) {
                    $category = CategoryQuery::create()->findPk($product->getDefaultCategoryId());
                    $category->setLocale($locale);
                }

                $country = CountryQuery::create()->filterByByDefault(1)->findOne();
                if ($customer) {
                    $country = $customer->getDefaultAddress()->getCountry();
                }

                $purchaseTaxedPrice = $cartItem->getTotalRealTaxedPrice($country);

                $matomoTracker->addEcommerceItem(
                    $product->getRef(),
                    $product->setLocale($locale)->getTitle(),
                    $category?->getTitle(),
                    $purchaseTaxedPrice,
                    $cartItem->getQuantity()
                );
            }
        } catch (Exception $ex) {
            Tlog::getInstance()->addError(sprintf("Matomo cart items Tracker error : %s", $ex->getMessage()));
        }
    }
}