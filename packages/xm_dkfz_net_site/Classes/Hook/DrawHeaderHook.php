<?php

namespace Xima\XmDkfzNetSite\Hook;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class DrawHeaderHook
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function addPageInfos(array $configuration, PageLayoutController $parentObject): string
    {
        $pageInfo = $parentObject->pageinfo;

        if (!is_array($pageInfo)) {
            return '';
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/PageInfo.html');
        $view->assign('data', $pageInfo);

        if ($pageInfo['media']) {
            $files = $this->fileRepository->findByRelation('pages', 'media', (int)$pageInfo['uid']);
            $view->assign('files', $files);
        }

        if ($pageInfo['tx_xmdkfznetsite_contacts']) {
            $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
            $qb->getRestrictions()->removeAll();
            $users = $qb->select('*')
                ->from('fe_users')
                ->where(
                    $qb->expr()->in('uid', $pageInfo['tx_xmdkfznetsite_contacts'])
                )
                ->execute()
                ->fetchAllAssociative();

            $view->assign('users', $users);
        }

        return $view->render();
    }
}
