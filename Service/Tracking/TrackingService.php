<?php

namespace MatomoManager\Service\Tracking;

use MatomoManager\MatomoManager;

class TrackingService
{
    /**
     * @param string $action
     * @return array
     */
    public function getTrackerTemplatePrameters(string $action): array
    {
        switch ($action) {
            case 'integration_script':
                $parameters = [
                    'integration_script' => MatomoManager::getConfigValue('matomo_integration_script')
                ];
                break;

            case 'analytics':
                $url = MatomoManager::getConfigValue('matomo_url');
                preg_match('/(https?:\/\/)(.*)/', $url, $matches);

                $parameters = [
                    'URL_MATOMO' => $matches[2],
                    'SITE_ID' => MatomoManager::getConfigValue('matomo_site_id'),
                    'CONSENT_TRACKING' => MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking')
                ];
                break;

            case 'tag_manager':
                $parameters = [
                    'URL_MATOMO' => MatomoManager::getConfigValue('matomo_url'),
                    'CONTAINER' => MatomoManager::getConfigValue('matomo_tag_manager_container'),
                    'TAG_MANAGER_ENV' => MatomoManager::getConfigValue('matomo_tag_manager_env', 'live')
                ];
                break;
        }

        return $parameters;
    }
}