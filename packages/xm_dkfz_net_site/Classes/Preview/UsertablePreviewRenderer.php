<?php

namespace Xima\XmDkfzNetSite\Preview;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Preview\TextmediaPreviewRenderer;

class UsertablePreviewRenderer extends TextmediaPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $item->getRecord();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $users = $queryBuilder->select('*')
            ->from('fe_users')
            ->join(
                'fe_users',
                'tt_content_item',
                't',
                $queryBuilder->expr()->eq('fe_users.uid', $queryBuilder->quoteIdentifier('t.fe_user'))
            )
            ->where($queryBuilder->expr()->eq('t.foreign_uid', (int)$row['uid']))
            ->orderBy('t.sorting')
            ->execute()
            ->fetchAllAssociative();

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $returnUrl = (string)$uriBuilder->buildUriFromRoute('web_layout', ['id' => $row['pid']]);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/Usertable.html');
        $view->assign('row', $row);
        $view->assign('users', $users);
        $view->assign('returnUrl', $returnUrl);

        return $view->render();
    }
}
