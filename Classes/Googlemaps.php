<?php

/***************************************************************
 *  Copyright notice
 *  (c) 2014 Christian Bülter
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Tpwd\KeSearchPremium;

use Tpwd\KeSearch\Lib\SearchHelper;
use Tpwd\KeSearch\Plugins\ResultlistPlugin;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class Googlemaps implements \TYPO3\CMS\Core\SingletonInterface
{
    public $extKey = 'ke_search_premium';
    public $mapHeaderCode = '';
    public $mapsMarkerArray = array();

    public function __construct()
    {
        // get extension configuration
        $this->extConf = SearchHelper::getExtConf();
        $this->extConfPremium = SearchHelper::getExtConfPremium();
        $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kesearchpremium.']['distancesearch.'] ?? [];
    }

    /**
     * add map to result list
     * @param array $fluidTemplateVariables
     * @param ResultlistPlugin $kesearchPi2
     * @author Christian Bülter
     * @since 07.07.14
     * @return void
     */
    public function modifyResultList(&$fluidTemplateVariables, ResultlistPlugin $kesearchPi2)
    {
        if ($this->extConfPremium['enableDistanceSearch']) {
            // fetch zip (or city) and the radius from the search form
            if (isset($kesearchPi2->piVars['filter'])) {
                $zip = htmlspecialchars($kesearchPi2->piVars['filter']['distancesearch-zip'] ?? '');
                $radius = htmlspecialchars($kesearchPi2->piVars['filter']['distancesearch-radius'] ?? '');
            } else {
                $zip = '';
                $radius = '';
            }

            // show map only if zip and radius has been given in the search form
            if ($zip && $radius) {
                // show map only if there's at least one result to be displayed on the map
                if (count($this->mapsMarkerArray)) {
                    // show map only if the geocoding of the  city in the search form has been successful
                    $geoLocation = Geocode::getCoordinatesForCity(
                        $zip,
                        $this->extConfPremium['country']
                    );
                    if (is_array($geoLocation) && count($geoLocation) && $geoLocation['lat'] && $geoLocation['lng']) {
                        $mapHtmlCode = $this->placeMarkersOnMap($this->renderGoogleMap($geoLocation, $radius));
                        $GLOBALS['TSFE']->additionalHeaderData['tx_kesearch_premium_map'] = $mapHtmlCode;

                        if ($this->extConfPremium['addMapToDefaultTemplate']) {
                            $fluidTemplateVariables['resultListAdditionalRawContent'] = '<div id="map-canvas"></div>';
                        }
                    }
                }
            }
        }
    }

    /**
     * render the google map html code
     * @param array $geoLocation
     * @param integer $radius
     * @return string
     */
    public function renderGoogleMap($geoLocation, $radius)
    {
        // calculate zoom
        $zoom = 10 - round($radius / 30);
        if ($zoom < 6) {
            $zoom = 6;
        }

        // instantiate fluid standalone view
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $templateFile = GeneralUtility::getFileAbsFileName($this->conf['templatepath'] . 'googlemaps.html');
        $view->setTemplatePathAndFilename($templateFile);
        $view->assign('lat', $geoLocation['lat']);
        $view->assign('lon', $geoLocation['lng']);
        $view->assign('zoom', $zoom);
        $view->assign('googleapikey', $this->extConfPremium['googleapikeybrowser']);

        $content = $view->render();
        return $content;
    }

    /**
     * creates the Google Map markers
     * @param string $mapHtmlCode
     * @return string
     */
    public function placeMarkersOnMap($mapHtmlCode)
    {
        if (count($this->mapsMarkerArray)) {
            foreach ($this->mapsMarkerArray as $row) {
                $markerPlaceholder = '<!--###MAPSMARKER###-->';
                $markerJs = 'var marker' . $row['uid'] . ' = new google.maps.Marker({'
                    . 'position:new google.maps.LatLng(' . $row['lat'] . ',' . $row['lon'] . '),'
                    . 'map:map,'
                    . 'title: "' . htmlspecialchars(strip_tags($row['title']), ENT_QUOTES, 'UTF-8') . '"});' . "\n";

                $mapHtmlCode = str_replace(
                    $markerPlaceholder,
                    $markerJs . $markerPlaceholder,
                    $mapHtmlCode
                );
            }
            $mapHtmlCode = str_replace($markerPlaceholder, '', $mapHtmlCode);
        }

        return $mapHtmlCode;
    }

    /**
     * put the result in the array of markers to be displayed on the map
     * @param array $row
     */
    public function addMarkerToMap($row)
    {
        if ($row['lat'] && $row['lon']) {
            $this->mapsMarkerArray[] = $row;
        }
    }
}
