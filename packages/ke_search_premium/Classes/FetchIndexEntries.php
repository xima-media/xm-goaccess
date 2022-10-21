<?php

namespace Tpwd\KeSearchPremium;

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

use Tpwd\KeSearch\Lib\Db;
use Tpwd\KeSearch\Lib\SearchHelper;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FetchIndexEntries
{
    // extension configuration of ke_search (NOT of premium version)
    public $extConf = array();

    // extension configuration of ke_search_premium
    public $extConfPremium = array();

    function __construct()
    {
        $this->extConf = SearchHelper::getExtConf();
        $this->extConfPremium = SearchHelper::getExtConfPremium();
    }

    /**
     * Fetches index records from a given page.
     * Number is limited, if you need more records, you'll have to call the
     * function more than once. You will get then the records wich have not
     * been transfered yet.
     * @param integer $pageUid
     * @param integer $limit
     * @return array
     * @author Christian Bülter
     * @since 05.11.14
     */
    public function fetchIndexEntries($pageUid, $limit = 500)
    {
        $records = [];
        $transfertime = time();

        /* @var $cObj ContentObjectRenderer */
        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        // only transfer index records without frontend user protection
        $queryBuilder = Db::getQueryBuilder('tx_kesearch_index');
        $queryBuilder->getRestrictions()->removeAll();
        $results = $queryBuilder
            ->select('*')
            ->from('tx_kesearch_index')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT)
                )
            )
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq(
                        'fe_group',
                        $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        'fe_group',
                        $queryBuilder->createNamedParameter("", \PDO::PARAM_STR)
                    )
                )
            )
            ->setMaxResults($limit)
            ->orderBy('lastremotetransfer')
            ->addOrderBy('tstamp')
            ->execute()
            ->fetchAll();

        if (is_array($results) && count($results)) {
            foreach($results as $record) {
                // create link for this index record
                $linkConf = SearchHelper::getResultLinkConfiguration($record);
                $record['externalurl'] = $cObj->typoLink_URL($linkConf);

                // set timestamp for this record both in transfered record and in database
                $queryBuilder = Db::getQueryBuilder('tx_kesearch_index');
                $queryBuilder
                    ->update('tx_kesearch_index')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter(
                                $record['uid'], \PDO::PARAM_INT
                            )
                        )
                    )
                    ->set('lastremotetransfer', $transfertime)
                    ->execute();

                $record['lastremotetransfer'] = $transfertime;

                $records[] = $record;
            }
            return $records;
        } else {
            return ['error' => 'no records found'];
        }

    }
}
