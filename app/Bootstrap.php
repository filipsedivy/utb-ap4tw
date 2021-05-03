<?php

declare(strict_types = 1);

namespace App;

use App\Core\Configurator;

class Bootstrap
{

	public const MAIL_DIR = __DIR__ . '/Mail';

	public static function boot(): Configurator
	{
		$configurator = new Configurator;
		$appDir = \dirname(__DIR__);

		//$configurator->setDebugMode(''); // enable for your remote IP
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig($appDir . '/config/common.neon');

		if (\getenv('HEROKU_APPLICATION') !== false) {
			$configurator->addConfig($appDir . '/config/production.neon');
		} else {
			$configurator->addConfig($appDir . '/config/local.neon');
		}

		return $configurator;
	}

}
