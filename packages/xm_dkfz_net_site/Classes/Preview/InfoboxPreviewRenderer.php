<?php

namespace Xima\XmDkfzNetSite\Preview;

use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Preview\TextmediaPreviewRenderer;

class InfoboxPreviewRenderer extends TextmediaPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = parent::renderPageModulePreviewContent($item);
        $row = $item->getRecord();

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/InfoboxPreview.html');

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content_item');
        $links = $queryBuilder->select('*')
            ->from('tt_content_item')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('foreign_uid', (int)$row['uid']),
                    $queryBuilder->expr()->eq('foreign_table', $queryBuilder->createNamedParameter('tt_content'))
                )
            )
            ->orderBy('sorting')
            ->execute()
            ->fetchAllAssociative();

        if (empty($links)) {
            return $content;
        }

        $view->assign('content', $content);
        $view->assign('links', $links);

        return $view->render();
    }
}
