<?php

namespace MatomoManager\Service\Tracking\EventTracking;

use MatomoManager\MatomoManager;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\CategoryQuery;
use Thelia\Model\CountryQuery;
use Thelia\Model\Product;
use Thelia\Model\ProductSaleElements;

class ProductTrackingService
{
    /**
     * @param Product $product
     * @return void
     * @throws PropelException
     */
    public function trackProduct(Product $product) : void
    {
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
    }
}