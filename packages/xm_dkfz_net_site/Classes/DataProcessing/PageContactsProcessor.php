<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmDkfzNetSite\Domain\Repository\PlaceRepository;
use Xima\XmDkfzNetSite\Domain\Repository\UserRepository;

class PageContactsProcessor implements DataProcessorInterface
{
    protected FileRepository $fileRepository;

    protected RelationHandler $relationHandler;

    protected PlaceRepository $placeRepository;

    protected UserRepository $userRepository;

    public function __construct(
        FileRepository $fileRepository,
        RelationHandler $relationHandler,
        PlaceRepository $placeRepository,
        UserRepository $userRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->relationHandler = $relationHandler;
        $this->placeRepository = $placeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
     * @param array<int, mixed> $contentObjectConfiguration
     * @param array<int, mixed> $processorConfiguration
     * @param array<string, mixed> $processedData
     * @return array<string, mixed>
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
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

        if (isset($processedData['contacts']['tx_xmdkfznetsite_domain_model_place'])) {
            $uids = array_keys($processedData['contacts']['tx_xmdkfznetsite_domain_model_place']);
            $processedData['contacts']['tx_xmdkfznetsite_domain_model_place'] = $this->placeRepository->findByUids($uids);
        }

        if (isset($processedData['contacts']['fe_users'])) {
            $uids = array_keys($processedData['contacts']['fe_users']);
            $processedData['contacts']['fe_users'] = $this->userRepository->findByUids($uids);
        }

        return $processedData;
    }
}
