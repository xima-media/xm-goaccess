<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-accordion',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.description',
        [
            [
                ['name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.items', 'colPos' => 100, 'allowed' => ['CType' => 'container-accordion-item']],
            ],
        ]
    )
    )->setIcon('EXT:xm_dkfz_net_site/Resources/Public/Images/icon-container-accordion.svg')
);
