<?php

namespace Xima\XmDkfzNetSite\Preview;

use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Preview\TextmediaPreviewRenderer;

class TeaserPreviewRenderer extends TextmediaPreviewRenderer
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = parent::renderPageModulePreviewContent($item);
        $row = $item->getRecord();

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_dkfz_net_site/Resources/Private/Extensions/Backend/TeaserPreview.html');

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content_item');
        $children = $queryBuilder->select('*')
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

        if (!$children) {
            return $content;
        }

        foreach ($children as &$child) {
            // add content from selected page
            $this->fillChildFromLink($child);

            // resolve image
            if ($child['image']) {
                $child['files'] = $this->fileRepository->findByRelation('tt_content_item', 'image', $child['uid']);
            }

            // resolve link items
            $this->resolveChildLinkItems($child);
        }

        $view->assign('children', $children);

        return $view->render();
    }

    protected function resolveChildLinkItems(&$item)
    {
        if (!$item['tt_content_items']) {
            return;
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content_item');
        $qb->getRestrictions()->removeAll();
        $links = $qb->select('*')
            ->from('tt_content_item')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('deleted', 0),
                    $qb->expr()->eq('pid', $item['pid']),
                    $qb->expr()->eq('foreign_uid', $qb->createNamedParameter($item['uid'], \PDO::PARAM_INT))
                )
            )
            ->execute()
            ->fetchAllAssociative();

        if (!$links) {
            return;
        }

        $item['links'] = array_map(function ($link) {
            return ['link' => $link['link'], 'title' => $link['title']];
        }, $links);
    }

    protected function fillChildFromLink(&$item)
    {
        $pageUid = $item['link'] ?? '';
        $pageUid = substr($pageUid, 14);

        if (!MathUtility::canBeInterpretedAsInteger($pageUid)) {
            return;
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $qb->getRestrictions()->removeAll();
        $page = $qb->select('*')
            ->from('pages')
            ->where(
                $qb->expr()->eq('uid', $qb->createNamedParameter($pageUid, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAssociative();

        if (!$pageUid) {
            return;
        }

        $item['title'] = $item['title'] ?: $page['title'];
        $item['text'] = $item['text'] ?: $page['description'];
        $item['color'] = $item['color'] ?: $page['tx_xmdkfznetsite_color'];

        if (!$item['image'] && $page['media']) {
            $item['files'] = $this->fileRepository->findByRelation('pages', 'media', (int)$pageUid);
        }
    }
}
