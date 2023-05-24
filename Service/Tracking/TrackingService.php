<?php

namespace MatomoManager\Service\Tracking;

use Exception;
use MatomoManager\MatomoManager;

class TrackingService
{
    /**
     * @throws Exception
     */
    public function getTrackerTemplateParameters(string $action): array
    {
        $parameters = [];

        $url = MatomoManager::getConfigValue('matomo_url');
        $siteId = MatomoManager::getConfigValue('matomo_site_id');
        $integrationScript = MatomoManager::getConfigValue('matomo_integration_script');
        $container = MatomoManager::getConfigValue('matomo_tag_manager_container');

        //TODO: peut mieux faire, à refacto.
        switch ($action) {
            case 'integration_script':
                if (!$integrationScript) {
                    throw new Exception("Script missing.");
                }

                $parameters = [
                    'integration_script' => $integrationScript
                ];

                break;

            case 'analytics':
                if (!$url || !$siteId) {
                    throw new Exception("Matomo url or site id missing.");
                }

                preg_match('/(https?:\/\/)(.*)/', $url, $matches);

                $parameters = [
                    'URL_MATOMO' => $matches[2] ?? $url,
                    'SITE_ID' => $siteId,
                    'CONSENT_TRACKING' => MatomoManager::getConfigValue('matomo_ecommerce_consent_tracking')
                ];

                break;

            case 'tag_manager':
                if (!$url || !$container) {
                    throw new Exception("Matomo tag manager configuration missing.");
                }

                $parameters = [
                    'URL_MATOMO' => $url,
                    'CONTAINER' => $container,
                    'TAG_MANAGER_ENV' => MatomoManager::getConfigValue('matomo_tag_manager_env', 'live')
                ];

                break;
        }

        return $parameters;
    }
}