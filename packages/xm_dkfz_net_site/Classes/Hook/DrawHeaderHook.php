<?php

namespace Xima\XmDkfzNetSite\Hook;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Database\RelationHandler;
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

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/PageHeaderInfo.html');
        $view->assign('data', $pageInfo);

        if ($pageInfo['media']) {
            $files = $this->fileRepository->findByRelation('pages', 'media', (int)$pageInfo['uid']);
            $view->assign('files', $files);
        }

        if ($pageInfo['tx_xmdkfznetsite_contacts']) {
            $relationHandler = GeneralUtility::makeInstance(RelationHandler::class);
            $relationHandler->start(
                $pageInfo['tx_xmdkfznetsite_contacts'],
                $GLOBALS['TCA']['pages']['columns']['tx_xmdkfznetsite_contacts']['config']['allowed'],
                '',
                '',
                'pages',
                $GLOBALS['TCA']['pages']['columns']['tx_xmdkfznetsite_contacts']
            );
            $relationHandler->getFromDB();
            $users = $relationHandler->results;
            $view->assign('users', $users);
        }

        return $view->render();
    }
}
