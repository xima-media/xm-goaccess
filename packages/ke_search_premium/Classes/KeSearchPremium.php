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

namespace Tpwd\KeSearchPremium;

use Tpwd\KeSearch\Lib\Db;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use Tpwd\KeSearchPremium\Lib\SphinxClient;
use Tpwd\KeSearch\Lib\SearchHelper;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class KeSearchPremium
{

    /**
     * @var SphinxClient
     */
    public $sphinxObj;

    /**
     * contains the returned results of sphinx index
     * @var array
     */
    public $return = [];

    /**
     * extension configuration of ke_search (NOT of premium version)
     * @var array
     */
    public $extConf = [];

    /**
     * extension configuration of ke_search_premium
     * @var array
     */
    public $extConfPremium = [];

    /** @var Logger */
    private Logger $logger;

    /**
     * KeSearchPremium constructor.
     */
    public function __construct()
    {
        // get extension configuration array
        $this->extConf = SearchHelper::getExtConf();
        $this->extConfPremium = SearchHelper::getExtConfPremium();
        if (!$this->extConf['multiplyValueToTitle']) {
            $this->extConf['multiplyValueToTitle'] = 1;
        }

        $this->sphinxObj = GeneralUtility::makeInstance(SphinxClient::class);
        $this->sphinxObj->SetServer(
            $this->extConfPremium['sphinxServer'],
            intval($this->extConfPremium['sphinxPort'])
        );
        $this->sphinxObj->SetConnectTimeout(1);
        $this->sphinxObj->SetArrayResult(false); // if set to true there can be duplicates
        $this->sphinxObj->SetMatchMode(SPH_MATCH_EXTENDED2);
        $this->sphinxObj->SetFieldWeights(array('title' => intval($this->extConf['multiplyValueToTitle'])));
        $this->sphinxObj->SetRankingMode(SPH_RANK_PROXIMITY_BM25);

        /** @var LogManager */
        $logManager = GeneralUtility::makeInstance(LogManager::class);
        /** @var Logger $logger */
        $this->logger = $logManager->getLogger(__CLASS__);
    }

    /**
     * @param string $q The query to execute
     * @param string $index Work with following index
     * @param string $select With fields should be selected from MySQL-Server
     * @return array Array containing the searchresults
     */
    public function getSearchResults($q, $index = '*', $select = '*')
    {
        $this->return = $this->sphinxObj->Query($q, $index);

        // error check
        $error = $this->sphinxObj->GetLastError();
        if ($error) {
           $this->logger->error($error);
        }
        $warning = $this->sphinxObj->GetLastWarning();
        if ($warning) {
            $this->logger->warning($warning);
        }
        if (!empty($error) || !empty($warning)) {
            if (!empty($this->extConfPremium['sphinxAdminEmail'])) {
                mail(
                    $this->extConfPremium['sphinxAdminEmail'],
                    'Sphinx error on ' . $_SERVER['HTTP_HOST'],
                    $error . "\n" . $warning
                );
            }
        }

        if (is_array($this->return['matches'] ?? null) && count($this->return['matches'])) {
            // workaround for large result sets
            // TODO: optimize query so that this large amount of memory is not needed
            if (count($this->return['matches']) > 50000) {
                ini_set('memory_limit', '2048M');
            }

            $idListArray = array_keys($this->return['matches']);
            $ids = implode(',', $idListArray);

            // reduce sorting to first $sortingLimit elements in order to improve performance
            // ordering has to be reverse because we have to use DESC in mysql later.
            // not using desc sorting would result in putting all the
            // unsorted results at the beginning which we don't want.
            $sortingLimit = 1000;
            $idsChunked = array_chunk($idListArray, $sortingLimit, true);
            $idsSorting = implode(',', array_reverse($idsChunked[0], true));

            $queryBuilder = Db::getQueryBuilder('tx_kesearch_index');
            $queryBuilder->getRestrictions()->removeAll();
            $rows = $queryBuilder
                ->addSelect($select)
                ->addSelectLiteral('FIELD(`uid`, ' . $idsSorting . ') AS `sortfield`')
                ->from('tx_kesearch_index')
                ->where(
                    $queryBuilder->expr()->in(
                        'uid',
                        $queryBuilder->createNamedParameter(
                            $idListArray,
                            Connection::PARAM_INT_ARRAY
                        )
                    )
                )
                ->orderBy('sortfield', 'DESC')
                ->execute()
                ->fetchAll();

            return $rows;
        } else {
            return array();
        }
    }

    /**
     * set limits for search
     * @param integer $start
     * @param integer $limit
     * @param integer $max
     */
    public function setLimit($start = 0, $limit = 10, $max = 250000)
    {
        $this->sphinxObj->SetLimits((int)$start, (int)$limit, (int)$max);
    }

    /**
     * sorting mapper for sphinx
     * @param string $sort Something like "sortdate asc"
     * @return void
     */
    public function setSorting($sort)
    {
        $sorting = GeneralUtility::trimExplode(' ', $sort);
        if ($sorting[1] == 'asc') {
            if ($sorting[0] == 'score') {
                $this->sphinxObj->SetSortMode(SPH_SORT_EXTENDED, '@weight asc');
            } else {
                $this->sphinxObj->SetSortMode(SPH_SORT_ATTR_ASC, $sorting[0]);
            }
        } else {
            if ($sorting[0] == 'score') { // score needs a special handling
                $this->sphinxObj->SetSortMode(SPH_SORT_RELEVANCE);
            } else {
                $this->sphinxObj->SetSortMode(SPH_SORT_ATTR_DESC, $sorting[0]);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTotalFound()
    {
        return $this->return['total_found'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getLastWarning()
    {
        return $this->sphinxObj->GetLastWarning();
    }

    /**
     * @return mixed
     */
    public function getLastError()
    {
        return $this->sphinxObj->GetLastError();
    }
}
