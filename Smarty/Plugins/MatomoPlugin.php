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
    public function getPluginDescriptors(): mixed
    {
        return [
            new SmartyPluginDescriptor("function", "track_search", $this, "trackSearch")
        ];
    }

    /**
     * Assign meta title, description and keyword for the template
     *
     * @param array $params
     */
    public function trackSearch($params)
    {
        if (!MatomoManager::getConfigValue('matomo_ecommerce_track_search')) {
            return;
        }

        $search = $params['search'] ?? null;
        $searchCategory = $params['search_category'] ?? '';
        $searchCount = $params['search_count'] ?? null;

        if (!$search) {
            return;
        }

        if (strlen($search) < 3) {
            return;
        }

        $this->searchTrackingService->trackSearch($search, $searchCategory, $searchCount);
    }
}