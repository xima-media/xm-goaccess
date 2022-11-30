<?php

/***************************************************************
 *  Copyright notice
 *  (c) 2011 Stefan Froemken
 *  (c) 2016 Christian Bülter
 *  (c) 2019 Andreas Kiefer
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

namespace Tpwd\KeSearchPremium\Middleware;

use TYPO3\CMS\Core\Http\Response;
use Tpwd\KeSearch\Lib\Db;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Autocomplete
{

    /** @var string */
    protected $content = '';

    /**
     * This method tries to find word which starts with the value of $begin
     * It retrieves its data out of the statistics table of ke_search.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response only for backwards compatibility with TYPO3 9
     * @return ResponseInterface
     */
    public function findWordsWhichBeginsWith(ServerRequestInterface $request, ResponseInterface $response = NULL)
    {
        // in TYPO3 9 we get two parameters: $request and $response
        // since TYPO3 10 we get only one parameter: $request
        if ($response === NULL) {
            unset($response);
            $response = new Response();
        }

        $begin = htmlspecialchars(GeneralUtility::_GP('wordStartsWith'));
        $amountValue = intval(GeneralUtility::_GP('amount'));
        $amount = $amountValue > 0 ? $amountValue : 10;
        $pid = intval(GeneralUtility::_GP('pid'));
        $words = [];

        if (!empty($begin)) {
            $rows = $this->getWords($begin, $amount);
        }

        if (is_array($rows) && count($rows)) {
            foreach ($rows as $row) {
                $words[] = $row['searchphrase'];
            }
        }

        // hook for custom modifications of autocomplete words
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['ke_search_premium']['modifyAutocompleWordList'] ?? '')) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['ke_search_premium']['modifyAutocompleWordList'] as $_classRef) {
                $_procObj = GeneralUtility::makeInstance($_classRef);
                $_procObj->modifyAutocompleWordList($words, $begin, $amount, $pid);
            }
        }

        if (count($words)) {
            $this->content = json_encode($words);
        } else {
            $this->content = json_encode('');
        }

        $response->getBody()->write($this->content);
        return $response;
    }

    /**
     * @param $begin
     * @param $amount
     * @return mixed[]
     */
    protected function getWords($begin, $amount)
    {
        $table = 'tx_kesearch_stat_search';
        $fields = 'searchphrase';
        $queryBuilder = Db::getQueryBuilder($table);

        $rows = $queryBuilder
            ->select($fields)
            ->from($table)
            ->where(
                $queryBuilder->expr()->like(
                    'searchphrase',
                    $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($begin) . '%')
                ),
                $queryBuilder->expr()->gt(
                    'hits',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->groupBy('searchphrase')
            ->setMaxResults($amount)
            ->orderBy('searchphrase')
            ->execute()
            ->fetchAll();

        return $rows;
    }

}
