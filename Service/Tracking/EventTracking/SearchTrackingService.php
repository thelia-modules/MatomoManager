<?php

namespace MatomoManager\Service\Tracking\EventTracking;

use MatomoManager\MatomoManager;

class SearchTrackingService
{
    /**
     * @param string $search
     * @param string $searchCategory
     * @param int $searchCount
     * @return void
     */
    public function trackSearch(string $search, string $searchCategory, int $searchCount) : void
    {
        $matomoTracker = MatomoManager::buildApiTracker();

        $matomoTracker->doTrackSiteSearch(
            $search,
            $searchCategory,
            $searchCount
        );
    }
}