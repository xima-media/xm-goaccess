<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook;

use Doctrine\DBAL\Connection as ConnectionAlias;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Tca\TcaUtility;

class DataHandlerHook
{
    /**
     * @param array<mixed> $incomingFieldArray
     * @param string $table
     * @param int $id
     * @param DataHandler $parentObj
     */
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        mixed $id,
        DataHandler $parentObj
    ): void {
        if (isset($incomingFieldArray['tx_xmdkfznetsite_color']) && ($table === 'pages' || $table === 'tx_news_domain_model_news')) {
            $color = $incomingFieldArray['tx_xmdkfznetsite_color'];

            if ('' === $color || !in_array($color, TcaUtility::$colors, true)) {
                $incomingFieldArray['tx_xmdkfznetsite_color'] = TcaUtility::getRandomColor();
            }
        }

        if ($table === 'tt_content' && isset($incomingFieldArray['CType']) && $incomingFieldArray['CType'] === 'container-accordion' && isset($incomingFieldArray['tt_content_items'])) {
            $tt_content_items_after = array_values(GeneralUtility::trimExplode(
                ',',
                $incomingFieldArray['tt_content_items'],
                true
            ));

            $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content_item');
            $qb->getRestrictions()->removeByType(HiddenRestriction::class);
            $tt_content_items_before = $qb->select('uid')
                ->from('tt_content_item')
                ->where(
                    $qb->expr()->andX(
                        $qb->expr()->eq('foreign_uid', $qb->createNamedParameter($id, \PDO::PARAM_INT)),
                        $qb->expr()->eq('foreign_table', $qb->createNamedParameter('tt_content', \PDO::PARAM_INT)),
                    )
                )
                ->orderBy('sorting')
                ->execute()
                ->fetchAllAssociative();

            $tt_content_items_before = array_map(function ($result) {
                return (string)$result['uid'];
            }, $tt_content_items_before);

            $moveMap = [];
            foreach ($tt_content_items_after as $pos => $uid) {
                // no movement
                if (isset($tt_content_items_before[$pos]) && $tt_content_items_before[$pos] === $uid) {
                    continue;
                }

                // look for old position to update
                $oldPos = array_search($uid, $tt_content_items_before);
                if ($oldPos !== false && $oldPos >= 0) {
                    $moveMap[($oldPos + 1) * 100] = ($pos + 1) * 100;
                }
            }

            $deletedPos = [];
            foreach ($tt_content_items_before as $pos => $uid) {
                $isStillThere = in_array($uid, $tt_content_items_after);
                if ($isStillThere === false) {
                    $deletedPos[] = ($pos + 1) * 100;
                }
            }

            if (count($deletedPos)) {
                $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
                $qb->getRestrictions()->removeAll();
                $qb->update('tt_content')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->eq('tx_container_parent', $qb->createNamedParameter($id, \PDO::PARAM_INT)),
                            $qb->expr()->in(
                                'colPos',
                                $qb->createNamedParameter($deletedPos, ConnectionAlias::PARAM_STR_ARRAY)
                            ),
                        )
                    )
                    ->set('deleted', 1)
                    ->executeStatement();
            }

            $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $qb->getRestrictions()->removeAll();
            $accordionChildren = $qb->select('*')
                ->from('tt_content')
                ->where(
                    $qb->expr()->eq('tx_container_parent', $qb->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->execute()
                ->fetchAllAssociative();

            $updateMap = ['tt_content' => []];
            foreach ($accordionChildren as $key => $child) {
                if (!isset($moveMap[$child['colPos']])) {
                    continue;
                }

                // set new colPos
                $updateMap['tt_content'][$child['uid']] = [
                    'tx_container_parent' => $id,
                    'colPos' => $moveMap[$child['colPos']],
                ];
            }

            if (count($updateMap['tt_content'])) {
                $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
                $dataHandler->start($updateMap, []);
                $dataHandler->process_datamap();
            }
        }
    }
}
