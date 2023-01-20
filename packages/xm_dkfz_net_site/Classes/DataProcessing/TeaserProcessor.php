<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class TeaserProcessor implements DataProcessorInterface
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $pageUid = $processedData['data']['link'] ?? '';
        $pageUid = substr($pageUid, 14);

        if (!MathUtility::canBeInterpretedAsInteger($pageUid)) {
            return $processedData;
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $qb->getRestrictions()->removeAll();
        $page = $qb->select('*')
            ->from('pages')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('uid', $qb->createNamedParameter($pageUid, \PDO::PARAM_INT)),
                    $qb->expr()->eq('l10n_parent', $qb->createNamedParameter($pageUid, \PDO::PARAM_INT))
                )
            )
            ->andWhere(
                $qb->expr()->eq('sys_language_uid',
                    $qb->createNamedParameter($processedData['data']['sys_language_uid'], \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAssociative();

        if (!$pageUid) {
            return $processedData;
        }

        // set texts from page if no override exists
        $processedData['data']['title'] = $processedData['data']['title'] ?: $page['title'] ?? '';
        $processedData['data']['text'] = $processedData['data']['text'] ?: $page['description'] ?? '';
        $processedData['data']['color'] = $processedData['data']['color'] ?: $page['tx_xmdkfznetsite_color'] ?? '';

        // set the page image if no override image set
        if (empty($processedData['files']) && $page && $page['media']) {
            $processedData['files'] = $this->fileRepository->findByRelation('pages', 'media', (int)$pageUid);
        }

        // set selected page as link if no links from overrides
        if (empty($processedData['links'])) {
            $processedData['links'] = [
                0 => [
                    'data' => [
                        'link' => 't3://page?uid=' . $pageUid,
                    ],
                ],
            ];
        }

        return $processedData;
    }
}
