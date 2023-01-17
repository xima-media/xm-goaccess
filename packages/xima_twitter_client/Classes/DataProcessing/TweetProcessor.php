<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Xima\XimaTwitterClient\DataProcessing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XimaTwitterClient\Domain\Model\Tweet;

/**
 * Fetch records from the database, using the default .select syntax from TypoScript.
 * This way, e.g. a FLUIDTEMPLATE cObject can iterate over the array of records.
 * Example TypoScript configuration:
 * 10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
 * 10 {
 *   table = tt_address
 *   pidInList = 123
 *   where = company="Acme" AND first_name="Ralph"
 *   orderBy = sorting DESC
 *   as = addresses
 *   dataProcessing {
 *     10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
 *     10 {
 *       references.fieldName = image
 *     }
 *   }
 * }
 * where "as" means the variable to be containing the result-set from the DB query.
 */
class TweetProcessor implements DataProcessorInterface
{
    /**
     * Fetches records from the database as an array
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $accounts = $cObj->getRecords('tx_ximatwitterclient_domain_model_account', [
            'uidInList.' => [
                'field' => 'twitter',
            ],
            'pidInList' => 0,
        ]);

        $accountUids = array_map(function ($account) {
            return $account['uid'];
        }, $accounts);

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_ximatwitterclient_domain_model_tweet');
        $query = $qb->select('*')
            ->from('tx_ximatwitterclient_domain_model_tweet')
            ->where(
                $qb->expr()->in('account', $qb->quoteArrayBasedValueListToStringList($accountUids))
            )
            ->orderBy('date', 'DESC');

        $maxItemConf = $processorConfiguration['maxItems'] ?? '';
        $maxItems = MathUtility::canBeInterpretedAsInteger($maxItemConf) ? (int)$maxItemConf : 0;
        if ($maxItems) {
            $query->setMaxResults($maxItemConf);
        }

        $results = $query->execute()->fetchAllAssociative();

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $tweets = $dataMapper->map(Tweet::class, $results);

        $processedData['tweets'] = $tweets;

        return $processedData;
    }
}
