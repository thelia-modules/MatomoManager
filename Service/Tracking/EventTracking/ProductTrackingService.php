<?php

namespace MatomoManager\Service\Tracking\EventTracking;

use Exception;
use MatomoManager\MatomoManager;
use Thelia\Log\Tlog;
use Thelia\Model\CategoryQuery;
use Thelia\Model\CountryQuery;
use Thelia\Model\Product;
use Thelia\Model\ProductSaleElements;

class ProductTrackingService
{
    public function trackProduct(Product $product): void
    {
        try {
            $matomoTracker = MatomoManager::buildApiTracker();

            /** @var ProductSaleElements $pse */
            $pse = $product->getProductSaleElementss()->getFirst();
            $category = CategoryQuery::create()->findPk($product->getDefaultCategoryId());

            $matomoTracker->setEcommerceView(
                $product->getRef(),
                $product->getTitle(),
                $category->getTitle(),
                $product->getTaxedPrice(
                    CountryQuery::create()->filterByByDefault(1)->findOne(),
                    $pse->getPrice()
                )
            );
        } catch (Exception $ex) {
            Tlog::getInstance()->addError(sprintf("Matomo product Tracker error : %s", $ex->getMessage()));
        }
    }
}