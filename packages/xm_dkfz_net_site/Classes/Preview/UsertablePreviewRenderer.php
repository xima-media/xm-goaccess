<?php

namespace Xima\XmDkfzNetSite\Preview;

use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

        if (!$users || !is_array($users) || !count($users)) {
            return '';
        }

        $content = '<ul>';
        foreach ($users as $user) {
            $content .= '<li>' . $user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['username'] . ')</li>';
        }
        $content .= '</ul>';

        return $content;
    }
}
