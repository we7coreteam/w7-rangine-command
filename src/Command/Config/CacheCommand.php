<?php

/**
 * Rangine Command Tool
 *
 * (c) We7Team 2019 <https://www.rangine.com>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com for more details
 */

namespace W7\Command\Command\Config;

use W7\App;
use W7\Console\Command\CommandAbstract;

class CacheCommand extends CommandAbstract {
	protected $description = 'create config cache file';

	protected function handle($options) {
		$this->call('config:clear');

		$configCachedPath = App::getApp()->getConfigCachePath();
		if (!file_exists($configCachedPath)) {
			mkdir($configCachedPath, 0777, true);
		}

		$configFileTree = glob(BASE_PATH . '/config/*.php');
		if (empty($configFileTree)) {
			$this->output->note('nothing to cache');
			return false;
		}

		foreach ($configFileTree as $path) {
			$key = pathinfo($path, PATHINFO_FILENAME);

			file_put_contents(
				$configCachedPath . $key . '.php',
				'<?php return ' . var_export(iconfig()->getUserConfig($key), true) . ';'
			);
		}

		$this->output->success('Config cached successfully!');
	}
}