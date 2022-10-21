<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2016 Christian Bülter
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

use Tpwd\KeSearch\Lib\Db;
use Tpwd\KeSearch\Lib\Pluginbase;
use Tpwd\KeSearch\Lib\SearchHelper;
use Tpwd\KeSearch\Lib\Searchphrase;
use Tpwd\KeSearch\Plugins\ResultlistPlugin;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class Distancesearch
{
    public $extKey = 'ke_search_premium';

    public function __construct()
    {
        // get extension configuration
        $this->extConf = SearchHelper::getExtConf();
        $this->extConfPremium = SearchHelper::getExtConfPremium();
        $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kesearchpremium.']['distancesearch.'] ?? [];
    }

    /**
     * Register additional fields for geocoding. This needs to be done
     * in order to store them into the index.
     * @param array $additionalFields
     * @author Christian Bülter
     * @since 02.07.14
     */
    public function registerAdditionalFields(&$additionalFields)
    {
        $additionalFields[] = 'lat';
        $additionalFields[] = 'lon';
    }

    /**
     * Modify index entry before it goes into the database,
     * add geocoding information.
     * @param string $title
     * @param string $abstract
     * @param string $fullContent
     * @param string $params
     * @param string $tagContent
     * @param array $addressRow
     * @param array $additionalFields
     * @param array $indexerConfig
     * @param array $customfields
     * @author Christian Bülter
     * @since 02.07.14
     */
    public function modifyAddressIndexEntry(
        $title,
        $abstract,
        $fullContent,
        $params,
        $tagContent,
        $addressRow,
        &$additionalFields,
        $indexerConfig,
        $customfields
    ) {
        if ($this->extConfPremium['enableDistanceSearch']) {
            $address = '';
            if ($addressRow['address']) {
                $address .= $addressRow['address'];
            }
            if ($addressRow['zip']) {
                if ($address) {
                    $address .= ',';
                }
                $address .= $addressRow['zip'];
            }
            if ($addressRow['city']) {
                if ($address) {
                    $address .= ',';
                }
                $address .= $addressRow['city'];
            }
            if ($addressRow['country']) {
                if ($address) {
                    $address .= ',';
                }
                $address .= $addressRow['country'];
            } else {
                if ($this->extConfPremium['country']) {
                    if ($address) {
                        $address .= ',';
                    }
                    $address .= $this->extConfPremium['country'];
                }
            }

            // get lat and long values
            $geoLocation = Geocode::getLocation(
                $address,
                $this->extConfPremium['googleapikeyserver']
            );

            if (is_array($geoLocation) && count($geoLocation)) {
                $additionalFields['lat'] = $geoLocation['lat'];
                $additionalFields['lon'] = $geoLocation['lng'];
            }
        }
    }

    /**
     * register additional filter for the backend flexform field
     * @param array $config
     * @param integer $pid
     * @author Christian Bülter
     * @since 07.07.14
     */
    public function customFilterFlexformEntry(array &$config, $pid)
    {
        if ($this->extConfPremium['enableDistanceSearch']) {
            $config['items'][] = array(
                $GLOBALS['LANG']->sL('LLL:EXT:ke_search_premium/Resources/Private/Language/locallang.xlf:customcategory.distancesearch'),
                'distance'
            );
        }
    }

    /**
     * @param integer $filterUid
     * @param array $options
     * @param Pluginbase $keSearchLibObj
     * @param array $filterData
     * @return void
     * @author Christian Bülter
     * @since 07.07.14
     */
    public function customFilterRenderer($filterUid, $options, Pluginbase $keSearchLibObj, &$filterData)
    {
        $table = 'tx_kesearch_filters';
        $queryBuilder = Db::getQueryBuilder($table);
        $filter = $queryBuilder
            ->select('rendertype')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        $filterUid, \PDO::PARAM_INT
                    )
                )
            )
            ->execute()
            ->fetch(0);

        // render filter with fluid
        if ($filter['rendertype'] == 'distance') {
            if (empty($this->conf['templatepath'] ?? '')) {
                $content = 'Distancesearch error: no template found (did you include the static template?).';
            } else {
                // instantiate fluid standalone view
                /** @var StandaloneView $view */
                $view = GeneralUtility::makeInstance(StandaloneView::class);
                $templateFile = GeneralUtility::getFileAbsFileName(
                    $this->conf['templatepath'] . 'distancesearchform.html'
                );
                $view->setTemplatePathAndFilename($templateFile);

                // assign variables
                if (isset($keSearchLibObj->piVars['filter'])) {
                    $zip = htmlspecialchars($keSearchLibObj->piVars['filter']['distancesearch-zip'] ?? '');
                    $radius = htmlspecialchars($keSearchLibObj->piVars['filter']['distancesearch-radius'] ?? '');
                    $view->assign('zip', $zip);
                    $view->assign('radius', $radius);
                }
                $view->assign('radiusoptions', explode(',', $this->conf['radiusoptions']));
                $view->assign('radiuslabel', $this->conf['radiuslabel']);

                // do the rendering
                $content = $view->render();
            }

            $filterData['rendertype'] = 'custom';
            $filterData['rawHtmlContent'] = $content;
        }
    }

    /**
     * Modify the tagsAgainst array and remove the filter for zip and radius
     * because these are no 'real' filters but will be added later to the
     * query.
     * @param array $tagsAgainst
     * @param Searchphrase $libSearchphraseObject
     * @author Christian Bülter
     * @since 07.07.14
     */
    public function modifyTagsAgainst(array &$tagsAgainst, Searchphrase $libSearchphraseObject)
    {
        unset($tagsAgainst['distancesearch-zip']);
        unset($tagsAgainst['distancesearch-radius']);
    }

    /**
     * modify the query parts in order to add the distance related parts
     * @param array $queryParts
     * @param Db $dbObject
     * @return array
     * @author Christian Bülter
     * @since 07.07.14
     */
    public function getQueryParts(array $queryParts, Db $dbObject)
    {
        $zip = $dbObject->pObj->piVars['filter']['distancesearch-zip'] ?? false;
        $radius = intval($dbObject->pObj->piVars['filter']['distancesearch-radius'] ?? 0);

        if ($zip && $radius) {
            $geoLocation = Geocode::getCoordinatesForCity(
                $zip,
                $this->extConfPremium['country'],
                $this->extConfPremium['googleapikeyserver']
            );
            if (is_array($geoLocation) && count($geoLocation)) {
                // add sql statements for distance calculation
                // http://gis.stackexchange.com/questions/31628/find-points-within-a-distance-using-mysql
                /*
                SELECT
                    id, (
                      6371 * acos (
                      cos ( radians(78.3232) )
                      * cos( radians( lat ) )
                      * cos( radians( lng ) - radians(65.3234) )
                      + sin ( radians(78.3232) )
                      * sin( radians( lat ) )
                    )
                ) AS distance
                FROM markers
                HAVING distance < 30
                ORDER BY distance
                */

                $queryParts['SELECT'] .=
                    ', ('
                    . '6371 * acos ( '
                    . 'cos ( radians(' . $geoLocation['lat'] . ') ) '
                    . '* cos( radians( lat ) ) '
                    . '* cos( radians( lon ) - radians(' . $geoLocation['lng'] . ') ) '
                    . '+ sin ( radians(' . $geoLocation['lat'] . ') ) '
                    . '* sin( radians( lat ) ))) AS distance';

                // Since $GLOBALS['TYPO3_DB']->exec_SELECTgetRows used in tx_kesearch_lib_searchphrase
                // does not support HAVING natively we use this kind of dirty hack.
                $queryParts['GROUPBY'] = 'uid HAVING distance <= ' . $radius;
                $queryParts['ORDERBY'] = 'distance ASC';
            }
        }
        return $queryParts;
    }

    /**
     * add distance to search result row
     * @param array $tempMarkerArray marker for one result
     * @param array $row search result row
     * @param Pluginbase $libObject
     * @author Christian Bülter
     * @since 07.07.14
     */
    public function additionalResultMarker(array &$tempMarkerArray, array $row, Pluginbase $libObject)
    {
        if ($this->extConfPremium['enableDistanceSearch']) {
            if ($row['distance'] ?? false) {
                $distance = '<span class="distance">' . round($row['distance']) . ' km</span>';
                $tempMarkerArray['distance'] = $distance;

                if ($this->extConfPremium['addDistanceToDefaultTemplate']) {
                    $tempMarkerArray['teaser'] .= ' ' . $distance;
                }
            } else {
                $tempMarkerArray['distance'] = '';
            }

            // add this result to map
            $googleMap = GeneralUtility::makeInstance(Googlemaps::class);
            $googleMap->addMarkerToMap($row);
        }
    }


    /**
     * remove order links once the distance filter is active
     * (ordering is then always "distance ASC")
     * @param string $content
     * @param ResultlistPlugin $kesearchPi2
     * @author Christian Bülter
     * @since 08.07.14
     */
    public function modifyResultList(&$content, ResultlistPlugin $kesearchPi2)
    {
        if ($this->extConfPremium['enableDistanceSearch']) {
            if (isset($kesearchPi2->piVars['filter'])) {
                $zip = htmlspecialchars($kesearchPi2->piVars['filter']['distancesearch-zip'] ?? '');
                $radius = htmlspecialchars($kesearchPi2->piVars['filter']['distancesearch-radius'] ?? '');
            } else {
                $zip = '';
                $radius = '';
            }
            if ($zip && $radius && !empty($content['sortingLinks'] ?? [])) {
                $content['sortingLinks'] = [
                    0 => [
                        'field' => '',
                        'url' => '',
                        'urlOnly' => '',
                        'class' => '',
                        'label' => LocalizationUtility::translate('distance', 'ke_search_premium')
                    ]
                ];
            }
        }
    }
}
