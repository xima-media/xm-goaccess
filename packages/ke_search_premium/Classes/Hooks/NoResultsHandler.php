<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2011 Stefan Froemken
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

namespace Tpwd\KeSearchPremium\Hooks;

use Tpwd\KeSearch\Lib\Pluginbase;
use Tpwd\KeSearch\Lib\SearchHelper;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * To use this object you have to install the sphinx server first.
 * Only with sphinx we have the possibility to find out how often a search word was found in the result
 * With the default search engine (MySQL) we only get the results but not the amount of results for each word
 * We will call the API of OpenThesaurus only if there are no results for a given search word
 */

/**
 * Class to build a text/image if no results were found
 */
class NoResultsHandler
{

    /**
     * @var Pluginbase
     */
    protected $pObj;

    protected $searchWords = [];
    protected $extKey = 'ke_search_premium';
    protected $extConf = [];
    protected $defaultLinkTemplateForXml = 'http://www.openthesaurus.de/synonyme/search?q=|&format=text/xml&similar=true';
    protected $defaultLinkTemplateForJson = 'http://www.openthesaurus.de/synonyme/search?q=|&format=application/json&similar=true';
    protected $defaultApiFormat = 'Json';

    /**
     * execute this handler if no results were found
     * @param string $noResultsText
     * @param Pluginbase $pObj
     * @return void
     */
    public function noResultsHandler(&$noResultsText, Pluginbase $pObj)
    {
        // initialize this object
        $this->init($pObj);

        // get similars and create links
        $similars = $this->buildSimilarArray();
        foreach ($similars as $similar) {
            $links[] = $this->buildNewSearchLink($similar);
        }

        if (!empty($links)) {
            $noResultsText = LocalizationUtility::translate(
                'noResultsTextOverwrite',
                'ke_search_premium'
            );
            $noResultsText .= implode(', ', $links);
        }
    }


    /**
     * Initializes this object
     * @param Pluginbase $pObj
     */
    public function init(Pluginbase $pObj)
    {
        $this->pObj = $pObj;
        $this->searchWords = $this->pObj->swords;
        $this->setExtConf(SearchHelper::getExtConfPremium());
    }


    /**
     * getter for extConf
     * @return array extConf
     */
    public function getExtConf()
    {
        return $this->extConf;
    }


    /**
     * setter for extConf
     * @param array $extConf
     * @return void
     */
    public function setExtConf(array $extConf)
    {
        // get API format
        if (!isset($extConf['apiFormat'])) {
            $extConf['apiFormat'] = 'Json';
        } else {
            $extConf['apiFormat'] = ucfirst($extConf['apiFormat']);
        }

        // get link template
        if (!isset($extConf['linkFor' . $extConf['apiFormat']])) {
            $default = 'defaultLinkTemplateFor' . $extConf['apiFormat'];
            $extConf['linkFor' . $extConf['apiFormat']] = $this->$default;
        };
        $this->extConf = $extConf;
    }


    /**
     * calls openthesaurus API
     * @param string $searchWord
     * @return array
     */
    public function getApiResultFor($searchWord)
    {
        $results = [];
        $link = $this->buildLinkForOpenThesaurus($searchWord);
        $content = GeneralUtility::getURL($link);
        $method = 'getResultFrom' . $this->extConf['apiFormat'];
        $results = array_merge($results, $this->$method($content));
        return $this->cleanSimilars($results);
    }


    /**
     * build link for open thesaurus
     * @param string Search word
     * @return string Link to get results from open thesaurus
     */
    public function buildLinkForOpenThesaurus($searchWord)
    {
        if (empty($searchWord)) {
            return '';
        } // search word must be filled
        $searchWord = rawurlencode($searchWord);

        // merge search words into link template
        return $this->pObj->cObj->wrap($searchWord, $this->extConf['linkFor' . $this->extConf['apiFormat']]);
    }


    /**
     * returns the result for similar words from xml
     * @param string $xml
     * @return array Array containing all similar words
     */
    public function getResultFromXml($xml)
    {
        if (is_string($xml) && !empty($xml)) {
            $xml = new \SimpleXMLElement($xml);
            if ($xml->similarterms->term !== NULL && $xml->similarterms->term->count()) {
                foreach ($xml->similarterms->term as $term) {
                    $result[] = array(
                        'term' => (string)$term['term'],
                        'distance' => (string)$term['distance'],
                    );
                }
                return $result;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }


    /**
     * returns the result for similar words from json
     * @param string $json
     * @return array Array containing all similar words
     */
    public function getResultFromJson($json)
    {
        if (is_string($json) && !empty($json)) {
            $json = json_decode($json, true);
            if (count($json['similarterms'])) {
                return $json['similarterms'];
            } else {
                return array();
            }
        } else {
            return array();
        }
    }


    /**
     * remove trash from the similar array
     * remove all entries which have a distance greater than 2
     * remove all entries which have ( and ) inside
     * remove all entries which are empty
     * @param array $similars
     * @return array A cleaned up similar array
     */
    public function cleanSimilars(array $similars)
    {
        $newSimilars = [];

        if (is_array($similars) && count($similars)) {
            foreach ($similars as $key => $similar) {
                // check term
                if (!is_string($similar['term']) || empty($similar['term'])) {
                    unset($similars[$key]);
                    continue;
                }
                // check distance
                if (intval($similar['distance']) > $this->extConf['maxDistance']) {
                    unset($similars[$key]);
                    continue;
                }
                // check wording
                // we don't need something like "(altes) Auto". We only need "Auto"
                $similars[$key]['term'] = preg_replace('/\(.*?\)/i', '', $similar['term']);
            }

            // make Array unique
            foreach ($similars as $key => $similar) {
                $newSimilars[trim($similar['term'])] = $similar;
            }
        }
        return $newSimilars;
    }


    /**
     * build an array with the synonyms of the search words
     * @return array Array containing the similars of the given search words
     */
    public function buildSimilarArray()
    {
        // get the searchWord from sphinx
        $similarArray = array();

        foreach ($this->searchWords as $word) {
            $similars = $this->getApiResultFor($word);
            if (is_array($similars) && count($similars)) {
                $counter = 0;
                foreach ($similars as $similar) {
                    // in case of the word "auto" you got nearly 50 results. This is to much
                    // so it's better to show only the first 5 synonyms
                    if ($counter >= 5) {
                        break;
                    }
                    $similarArray[] = array('old' => $word, 'new' => $similar);
                    $counter++;
                }
            }
        }
        return $similarArray;
    }


    /**
     * build a new search link with the new similar word
     * @param array $similar Contains old search word and an array with
     * information about the new similar words to this searchword
     * @return string HTML-Link
     */
    public function buildNewSearchLink(array $similar)
    {
        if (is_array($similar) && count($similar)) {
            // modify SearchString (cnuster auhto --> cluster auto)
            $searchString = str_replace($similar['old'], $similar['new']['term'], $this->pObj->sword);
            $searchString = strtolower($searchString);

            // create link to new search
            $conf['parameter'] = $GLOBALS['TSFE']->id;
            $conf['addQueryString'] = 1;
            $conf['addQueryString.']['exclude'] = 'tx_kesearch_pi1[sword]';
            $conf['additionalParams'] = '&tx_kesearch_pi1[sword]=' . htmlspecialchars($searchString);

            return $this->pObj->cObj->typoLink(htmlspecialchars($searchString), $conf);
        } else {
            return '';
        }
    }
}
