<?php

namespace MatomoManager;

use MatomoTracker;
use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Model\ConfigQuery;
use Thelia\Model\LangQuery;
use Thelia\Module\BaseModule;

class MatomoManager extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'matomotagmanager';

    /** @var string */
    const MODULE_CODE = 'MatomoManager';

    const CONFIGURATION_PARAMETERS = [
        'matomo_site_id',
        'matomo_url',
        'matomo_tag_manager_container',
        'matomo_tag_manager_env',
        'matomo_integration_script',
        'matomo_configuration_mode',
        'matomo_token_api',
        'matomo_ecommerce_track_order',
        'matomo_ecommerce_track_cart',
        'matomo_ecommerce_track_product',
        'matomo_ecommerce_track_search',
        'matomo_ecommerce_consent_tracking'
    ];

    /**
     * @return MatomoTracker
     */
    public static function buildApiTracker() : MatomoTracker
    {
        $matomoSiteId = MatomoManager::getConfigValue('matomo_site_id');
        $matomoUrl = MatomoManager::getConfigValue('matomo_url');
        $matomoToken = MatomoManager::getConfigValue('matomo_token_api');

        $matomoTracker = new MatomoTracker((int)$matomoSiteId, $matomoUrl);
        $matomoTracker->setTokenAuth($matomoToken);

        return $matomoTracker;
    }

    /**
     * Defines how services are loaded in your modules.
     */
    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode() . '\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR . ucfirst(self::getModuleCode()) . '/I18n/*'])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
