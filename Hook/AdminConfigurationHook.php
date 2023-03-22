<?php

namespace MatomoManager\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class AdminConfigurationHook extends BaseHook
{
    public function configuration(HookRenderEvent $event):void
    {
        $event->add(
            $this->render('matomo_configuration.html')
        );
    }

    public function configurationJs(HookRenderEvent $event):void
    {
        $event->add(
            $this->render('assets/js/module-configuration.js.html')
        );
    }
}