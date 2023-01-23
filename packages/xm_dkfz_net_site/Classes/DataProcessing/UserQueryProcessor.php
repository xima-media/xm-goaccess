<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmDkfzNetSite\Domain\Model\User;
use Xima\XmDkfzNetSite\Domain\Repository\UserRepository;

class UserQueryProcessor implements DataProcessorInterface
{

    public function __construct()
    {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');

        if ($processorConfiguration['type'] === 'latest') {
            $userResults = $qb->select('*')
                ->from('fe_users')
                ->orderBy('crdate', 'DESC')
                ->setMaxResults((int)$processorConfiguration['max'])
                ->execute()
                ->fetchAllAssociative();
        }

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $users = $dataMapper->map(User::class, $userResults ?? []);

        $processedData[$processorConfiguration['as']] = $users;

        return $processedData;
    }
}
