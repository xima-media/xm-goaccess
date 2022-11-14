<?php

namespace Xima\XmDkfzNetSite\Hook;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class DrawFooterHook
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param array<int, mixed> $configuration
     * @param \TYPO3\CMS\Backend\Controller\PageLayoutController $parentObject
     * @return string
     */
    public function addPageInfos(array $configuration, PageLayoutController $parentObject): string
    {
        $pageInfo = $parentObject->pageinfo;

        if (!is_array($pageInfo)) {
            return '';
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content_item');
        $children = $queryBuilder->select('tt_content_item.overrides', 'tt_content_item.link_title', 'p.media', 'p.tx_xmdkfznetsite_color', 'p.uid', 'p.title', 'tt_content_item.title as title_override', 'p2.title as parent_title')
            ->from('tt_content_item')
            ->join('tt_content_item', 'pages', 'p', $queryBuilder->expr()->eq('tt_content_item.page', $queryBuilder->quoteIdentifier('p.uid')))
            ->leftjoin('p', 'pages', 'p2',$queryBuilder->expr()->eq('p.pid', $queryBuilder->quoteIdentifier('p2.uid')))
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('foreign_uid', (int)$pageInfo['uid']),
                    $queryBuilder->expr()->eq('foreign_table', $queryBuilder->createNamedParameter('pages'))
                )
            )
            ->orderBy('tt_content_item.sorting')
            ->execute()
            ->fetchAllAssociative();

        if (!$children) {
            return '';
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/PageFooterInfo.html');

        foreach ($children as &$child) {
            // add content from selected page
            //$this->fillChildFromLink($child);

            // resolve image
            if ($child['media']) {
                $child['files'] = $this->fileRepository->findByRelation('pages', 'media', $child['uid']);
            }

            // resolve link items
            //$this->resolveChildLinkItems($child);
        }

        $view->assign('children', $children);

        return $view->render();
    }
}
