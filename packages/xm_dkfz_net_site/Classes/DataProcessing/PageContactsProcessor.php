<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class PageContactsProcessor implements DataProcessorInterface
{
    protected FileRepository $fileRepository;

    protected RelationHandler $relationHandler;

    public function __construct(FileRepository $fileRepository, RelationHandler $relationHandler)
    {
        $this->fileRepository = $fileRepository;
        $this->relationHandler = $relationHandler;
    }

    /**
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
     * @param array<int, mixed> $contentObjectConfiguration
     * @param array<int, mixed> $processorConfiguration
     * @param array<string, mixed> $processedData
     * @return array<string, mixed>
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if (!isset($processedData['data']['tx_xmdkfznetsite_contacts']) || !$processedData['data']['tx_xmdkfznetsite_contacts']) {
            return $processedData;
        }

        $this->relationHandler->start(
            $processedData['data']['tx_xmdkfznetsite_contacts'],
            $GLOBALS['TCA']['pages']['columns']['tx_xmdkfznetsite_contacts']['config']['allowed'],
            '',
            '',
            'pages',
            $GLOBALS['TCA']['pages']['columns']['tx_xmdkfznetsite_contacts']
        );
        $this->relationHandler->getFromDB();
        $processedData['contacts'] = $this->relationHandler->results;

        if (!isset($processedData['contacts']['fe_users'])) {
            return $processedData;
        }

        foreach ($processedData['contacts']['fe_users'] as &$feUser) {
            if ($feUser['logo']) {
                $sysFileReference = $this->fileRepository->findByRelation('fe_users', 'logo', $feUser['uid']);
                if (!empty($sysFileReference[0])) {
                    $feUser['logo'] = $sysFileReference[0];
                }
            }
        }

        return $processedData;
    }
}
