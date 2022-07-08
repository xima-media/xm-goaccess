<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-2cols',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.description',
        [
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.left',
                    'colPos' => 100,
                    'allowed' => ['CType' => '*'],
                ],
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.right',
                    'colPos' => 200,
                    'allowed' => ['CType' => '*'],
                ],
            ],
        ]
    )
    )->setIcon('content-container-columns-2')
);
