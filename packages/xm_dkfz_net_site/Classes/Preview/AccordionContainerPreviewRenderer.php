<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Preview;

/*
 * This file is part of TYPO3 CMS-based extension "container" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Container\Backend\Grid\ContainerGridColumn;
use B13\Container\Backend\Grid\ContainerGridColumnItem;
use B13\Container\ContentDefender\ContainerColumnConfigurationService;
use B13\Container\Domain\Factory\Exception;
use B13\Container\Domain\Factory\PageView\Backend\ContainerFactory;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\Grid;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridRow;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class AccordionContainerPreviewRenderer extends \B13\Container\Backend\Preview\ContainerPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = parent::renderPageModulePreviewContent($item);

        $row = $item->getRecord();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content_item');
        $children = $queryBuilder->select('*')
            ->from('tt_content_item')
            ->where($queryBuilder->expr()->eq('foreign_uid', (int)$row['uid']))
            ->orderBy('sorting')
            ->execute()
            ->fetchAllAssociative();

        $accordionItems = [];

        foreach ($children as $child) {
            $accordionItems[] = $child['title'];
        }

        $jsonDiv = '<div class="container-accordion-settings" data-accordion-items="' . urlencode(json_encode($accordionItems)) . '"></div>';

        return $content . $jsonDiv;
    }
}
