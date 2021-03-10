<?php

declare(strict_types=1);

namespace App;

use App\Core\Configurator;
use Tracy\Debugger;

class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator;
        $appDir = dirname(__DIR__);

        $configurator->setDebugMode(true); // enable for your remote IP
        $configurator->enableTracy($appDir . '/log');

        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig($appDir . '/config/common.neon');

        if (getenv('STAGE') !== false) {
            $configurator->addConfig($appDir . '/config/production.neon');
        } else {
            $configurator->addConfig($appDir . '/config/local.neon');
        }

        return $configurator;
    }
}
