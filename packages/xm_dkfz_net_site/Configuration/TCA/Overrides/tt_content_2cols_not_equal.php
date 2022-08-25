<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-2cols_not_equal',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.notEqual',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.description',
        [
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.left',
                    'colPos' => 100,
                ],
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.right',
                    'colPos' => 200,
                ],
            ],
        ]
    )
    )->setIcon('content-container-columns-2-left')
);
