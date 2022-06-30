<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-accordion-item',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion-item',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion-item.description',
        [
            [
                ['name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion-item.content', 'colPos' => 100, 'allowed' => ['CType' => 'textmedia']],
            ],
        ]
    )
    )->setIcon('EXT:xm_dkfz_net_site/Resources/Public/Images/icon-container-accordion-item.svg')
);
