<?php

/*****************************************************************
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
 * ************************************************************* */

namespace Tpwd\KeSearchPremium;

use SoapFault;
use SoapClient;
use Exception;
use Tpwd\KeSearch\Lib\SearchHelper;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class Fetchremote
{

    public $extConf = []; // extension configuration of ke_search (NOT of premium version)
    public $extConfPremium = []; // extension configuration of ke_search_premium

    /** @var \SoapClient */
    protected $client;

    /**
     * constructor for this class, fetches the basic extension configuration
     */

    function __construct()
    {
        // get extension configuration array
        $this->extConf = SearchHelper::getExtConf();
        $this->extConfPremium = SearchHelper::getExtConfPremium();
    }

    /**
     * the "fetch remote" indexer is not really an indexer, instead it fetches indexed data from a remote
     * TYPO3 instance with ke_search and ke_search_premium installed.
     * Adds the custom indexer to the TCA of indexer configurations, so that
     * it's selectable in the backend as an indexer type when you create a
     * new indexer configuration.
     * @param array $params
     * @param $pObj
     * @author Christian Bülter
     * @since 21. Nov 2014
     */
    function registerIndexerConfiguration(&$params, $pObj)
    {
        // add item to "type" field
        $newArray = array(
            $GLOBALS['LANG']->sL('LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_remote'),
            'remote',
            ExtensionManagementUtility::extPath('ke_search_premium') . 'Resources/Public/Media/remote.gif'
        );
        $params['items'][] = $newArray;
    }

    /**
     * Fetches index data from remote instance
     * @param   array $indexerConfig Configuration from TYPO3 Backend
     * @param   \Tpwd\KeSearch\Indexer\IndexerRunner $indexerObject Reference to indexer class.
     * @return  string Output.
     * @author Christian Bülter
     * @since 21. Nov 2014
     * @throws \Exception
     */
    public function customIndexer(&$indexerConfig, &$indexerObject)
    {
        if ($indexerConfig['type'] == 'remote') {
            $error = false;

            $content = '<p><b>Remote index data transfer</b></p>' . "\n";
            $content .= 'Connecting to ' . $indexerConfig['remotesite'] . '<br />' . "\n";

            // connect to soap server
            $this->connectToRemoteServer($indexerConfig);

            try {
                $results = $this->client->fetchIndexEntries(
                    intval($indexerConfig['remoteuid']),
                    intval($indexerConfig['remoteamount'])
                );
            } catch (Exception $e) {
                $indexerObject->logger->error($e->getMessage());
                $content .= 'Error: ' . $e->getMessage();
                $error = true;
            }

            if (is_array($results) && array_key_exists('error', $results)) {
                $indexerObject->logger->error($results['error']);
                $content .= 'Error: ' . $results['error'];
                $error = true;
            }

            if (!$error) {
                $content .= 'Fetching ' . count($results) . ' records.';
            }

            // Loop through the records and write them to the index.
            $count = 0;
            if (!$error && count($results)) {
                foreach ($results as $record) {

                    // compile the information which should go into the index
                    $record['type'] = 'external:' . $record['type'];

                    // build and check external url, log invalid urls
                    if (filter_var($record['externalurl'], FILTER_VALIDATE_URL)) {
                        $record['params'] = $record['externalurl'];
                    } else {
                        $record['params'] = rtrim($indexerConfig ['remotesite'], '/') . '/' . $record['externalurl'];
                    }
                    if (!filter_var($record['params'], FILTER_VALIDATE_URL)) {
                        $indexerObject->logger->error(
                            'invalid url for external index entry: ' . $record['params']
                        );
                    }

                    $additionalFields = array(
                        'orig_uid' => $record['orig_uid'],
                        'orig_pid' => $record['orig_pid'],
                        'sortdate' => $record['sortdate'],
                    );

                    // clean tags from tagChar (remove tagChar at the beginning and
                    // end of each tag), and re-compile the tag list since the
                    // tagChar of the remote site may differ from that on the
                    // local site
                    $tagChar = $this->extConf['prePostTagChar'];
                    $tags = GeneralUtility::trimExplode(',', $record['tags']);
                    foreach ($tags as $i => $tag) {
                        $tags[$i] = substr($tag, 1, strlen($tag) - 2);
                    }
                    $record['tags'] = '';
                    SearchHelper::makeTags($record['tags'], $tags);

                    // hook for custom modifications of the indexed data, e. g. the tags
                    if (
                        isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyRemoteIndexEntry']) &&
                        is_array($GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyRemoteIndexEntry'])
                    ) {
                        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyRemoteIndexEntry'] as $_classRef) {
                            $_procObj = GeneralUtility::makeInstance($_classRef);
                            $_procObj->modifyRemoteIndexEntry(
                                $record, $indexerConfig, $additionalFields
                            );
                        }
                    }

                    // store the information in the index
                    $indexerObject->storeInIndex(
                        $indexerConfig['storagepid'], // storage PID
                        $record['title'], // record title
                        $record['type'], // content type
                        $record['targetpid'], // target PID: where is the single view?
                        $record['content'], // indexed content, includes the title (linebreak after title)
                        $record['tags'] ?? '', // tags for faceted search
                        $record['params'], // typolink params for singleview
                        $record['abstract'] ?? '', // abstract; shown in result list if not empty
                        $record['sys_language_uid'] ?? 0, // language uid
                        $record['starttime'] ?? 0, // starttime
                        $record['endtime'] ?? 0, // endtime
                        $record['fe_group'] ?? '', // fe_group
                        false, // debug only?
                        $additionalFields               // additionalFields
                    );
                    $count++;
                }
                $content = '<p><b>Remote Indexer "' . $indexerConfig['title'] . '": ' . $count . ' Elements have been transfered.</b></p>';
            }

            return $content;
        }
    }

    /**
     * connects to remote server
     * @param array $indexerConfig
     * @return SoapClient / false
     * @author Christian Bülter
     * @since 21. Nov 2014
     * @throws SoapFault A SoapFault exception will be thrown if the wsdl URI cannot be loaded.
     */
    protected function connectToRemoteServer($indexerConfig)
    {
        $this->client = new \SoapClient(
            null, array(
                'location' => rtrim($indexerConfig['remotesite'], '/') . '/index.php?eID=tx_kesearchpremium_api',
                'uri' => 'http://tx_kesearchpremium_api',
                'trace' => 1
            )
        );

        $authHeader = new AuthHeader();
        $authHeader->username = $indexerConfig['remoteuser'];
        $authHeader->password = $indexerConfig['remotepass'];
        $Headers[] = new \SoapHeader('http://tx_kesearchpremium', 'AuthHeader', $authHeader);
        $this->client->__setSoapHeaders($Headers);
    }
}
