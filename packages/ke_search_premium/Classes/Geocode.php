<?php

/*****************************************************************
 *  Copyright notice
 *  (c) 2011 Andreas Kiefer
 *  (c) 2014 Christian Bülter
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

namespace Tpwd\KeSearchPremium;

use Tpwd\KeSearch\Indexer\IndexerRunner;
use Tpwd\KeSearchPremium\Service\CacheService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Geocode
{
    /** @var string  */
    static private $url = "https://maps.google.com/maps/api/geocode/json?sensor=false";

    /** @var string  */
    static private $cacheName = 'cache_kesearchpremium_geocode';

    /** @var int */
    static private $cacheLifetime = 60*60*24*7;

    /**
     *  find long and lat for the given city code or zip code
     * @param string $city
     * @param string $country
     * @param string $googleapikey
     * @return array
     * @author Christian Bülter
     * @since 07.07.14
     */
    public static function getCoordinatesForCity($city, $country = '', $googleapikey = '')
    {
        $address = $city;
        if ($country) {
            if ($address) {
                $address .= ',';
            }
            $address .= $country;
        }

        // do the geocoding
        return self::getLocation($address, $googleapikey);
    }

    /**
     * Stores gelococation information in
     * the TYPO3 cache (cf_cache_hash) in order to minimize requests to Google.
     * returns false if no geolocation could be found or an array containing
     * 'lat' and 'lng' entries.
     * @param string $address
     * @param string $googleapikey
     * @return mixed
     */
    public static function getLocation($address, $googleapikey = "")
    {
        // get instance of cache service and create hash key
        /** @var CacheService $cacheService */
        $cacheService = GeneralUtility::makeInstance(CacheService::class);
        $hashKey = md5(self::$cacheName . $address);

        // try to get the geolocation from cache
        if ($cacheService->hasCacheEntry($hashKey, self::$cacheName)) {
            $geoLocation = $cacheService->getCacheEntry($hashKey, self::$cacheName);
        } else {
            $geoLocation = self::getLocationFromGoogle($address, $googleapikey);
            $cacheService->setCacheEntry($hashKey, self::$cacheName, $geoLocation, [], self::$cacheLifetime);
        }
        return $geoLocation;
    }

    /**
     * fetches geolocation from Google using their API
     * @param $address string
     * @param $googleapikey string
     * @return mixed
     */
    public static function getLocationFromGoogle($address, $googleapikey = "")
    {
        $url = self::$url;

        // add address
        $url .= '&address=' . urlencode($address);

        // add api key
        if ($googleapikey) {
            $url .= '&key=' . $googleapikey;
        }

        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        $status = $resp['status'] ?? '';

        if ($status == 'OK') {
            return $resp['results'][0]['geometry']['location'];
        } else {
            /** @var IndexerRunner $indexerRunner */
            $indexerRunner = GeneralUtility::makeInstance(IndexerRunner::class);
            $indexerRunner->logger->warning('Geocoding failed for "' . $address . '": ' . $status);
            return false;
        }
    }

    /**
     * @param $URL
     * @return bool|string
     */
    private static function curl_file_get_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);
        if ($contents) {
            return $contents;
        } else {
            return false;
        }
    }

}
