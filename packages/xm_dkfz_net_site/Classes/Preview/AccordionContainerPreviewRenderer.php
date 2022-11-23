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
use B13\Container\Backend\Preview\ContainerPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AccordionContainerPreviewRenderer extends ContainerPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = parent::renderPageModulePreviewContent($item);

        $row = $item->getRecord();
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

        $accordionItems = [];

        foreach ($children as $child) {
            $accordionItems[] = $child['title'];
        }

        $jsonDiv = '<div class="container-accordion-settings" data-accordion-items="' . urlencode(json_encode($accordionItems)) . '"></div>';

        return $content . $jsonDiv;
    }
}
