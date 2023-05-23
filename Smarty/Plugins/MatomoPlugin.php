<?php

namespace MatomoManager\Smarty\Plugins;

use MatomoManager\MatomoManager;
use MatomoManager\Service\Tracking\EventTracking\SearchTrackingService;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

class MatomoPlugin extends AbstractSmartyPlugin
{
    public function __construct(protected SearchTrackingService $searchTrackingService)
    {

    }

    /**
     * @return SmartyPluginDescriptor[]
     */
    public function getPluginDescriptors(): array
    {
        return [
            new SmartyPluginDescriptor("function", "track_search", $this, "trackSearch")
        ];
    }

    /**
     * Use to track search
     */
    public function trackSearch(array $params): void
    {
        if (!MatomoManager::getConfigValue('matomo_ecommerce_track_search')) {
            return;
        }


        if (!$search = $params['search'] ?? null) {
            return;
        }

        if (strlen($search) < 3) {
            return;
        }

        $searchCategory = $params['search_category'] ?? '';
        $searchCount = $params['search_count'] ?? null;

        $this->searchTrackingService->trackSearch($search, $searchCategory, $searchCount);
    }
}