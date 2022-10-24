<?php

namespace Xima\XmManual\Preview;

use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Preview\TextmediaPreviewRenderer;

class MtextPreviewRenderer extends TextmediaPreviewRenderer
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $item->getRecord();

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_manual/Resources/Private/Backend/MboxPreview.html');
        $files = $this->fileRepository->findByRelation('tt_content', 'assets', $row['uid']);
        $view->assign('files', $files);
        $view->assign('data', $row);

        return $view->render();
    }
}
