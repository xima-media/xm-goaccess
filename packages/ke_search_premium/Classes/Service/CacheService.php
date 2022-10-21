<?php

namespace Tpwd\KeSearchPremium\Service;

/*****************************************************************
 *  Copyright notice
 *  (c) 2019 Andreas Kiefer
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @scope singleton
 */
class CacheService {

	protected $cacheInstances = array();

	/**
	 * @param $name
	 * @return \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
	 * @throws \Exception
	 */
	public function getInstance($name) {
		if(!array_key_exists($name, $this->cacheInstances)) {
			/** @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $cacheInstance */
			$this->cacheInstances[$name] = $this->createCacheInstance($name);
		}

		return $this->cacheInstances[$name];
	}

	/**
	 * @param $key
	 * @param $cache
	 * @return mixed
     * @throws \Exception
	 */
	public function getCacheEntry($key, $cache) {
		$cache = $this->getInstance($cache);
		return $cache->get($key);
	}

	/**
	 * @param $key
	 * @param $cache
	 * @param $lifetime
	 * @param array $tags
     * @throws \Exception
	 */
	public function setCacheEntry($key, $cache, $data, $tags = array(), $lifetime = NULL) {
		$cache = $this->getInstance($cache);
		$cache->set($key, $data, $tags, $lifetime);
	}

	/**
	 * @param $key
	 * @param $cache
	 * @return bool
     * @throws \Exception
	 */
	public function hasCacheEntry($key, $cache) {
		$cache = $this->getInstance($cache);
		return $cache->has($key);
	}

	/**
	 * @param $cache
     * @throws \Exception
	 */
	public function flushCache($cache) {
		$cache = $this->getInstance($cache);
		$cache->flush();
	}

	/**
	 * @param $cache
	 * @return \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
	 * @throws \Exception
	 */
	public function createCacheInstance($cache) {
		try {
			/** @var \TYPO3\CMS\Core\Cache\CacheManager $cacheManger */
			$cacheManger = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
			return $cacheManger->getCache($cache);
		} catch(\Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return array
	 */
	public function getCacheInstances() {
		return $this->cacheInstances;
	}
}
