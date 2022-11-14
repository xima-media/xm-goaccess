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
        $children = $queryBuilder->select('*')
            ->from('tt_content_item')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('foreign_uid', (int)$pageInfo['uid']),
                    $queryBuilder->expr()->eq('foreign_table', $queryBuilder->createNamedParameter('tt_content'))
                )
            )
            ->orderBy('sorting')
            ->execute()
            ->fetchAllAssociative();

        if (!$children) {
            return '';
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/PageFooterInfo.html');
        $view->assign('data', $pageInfo);

        foreach ($children as &$child) {
            // add content from selected page
            //$this->fillChildFromLink($child);

            // resolve image
            if ($child['image']) {
                $child['files'] = $this->fileRepository->findByRelation('tt_content_item', 'image', $child['uid']);
            }

            // resolve link items
            //$this->resolveChildLinkItems($child);
        }

        $view->assign('children', $children);

        return $view->render();
    }
}
