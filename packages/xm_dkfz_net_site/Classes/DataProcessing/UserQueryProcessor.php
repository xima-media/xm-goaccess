<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmDkfzNetSite\Domain\Model\User;

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

        // employee slider
        if ($processorConfiguration['type'] === 'latest') {
            $userResults = $qb->select('*')
                ->from('fe_users')
                ->where($qb->expr()->neq('fe_users.last_name', $qb->createNamedParameter('')))
                ->orderBy('crdate', 'DESC')
                ->setMaxResults((int)$processorConfiguration['max'])
                ->execute()
                ->fetchAllAssociative();
        }

        // select user for user table + responsibilities override
        if ($processorConfiguration['type'] === 'usertable' && $processedData['data']['fe_user']) {
            $userResults = $qb->select('*')
                ->from('fe_users')
                ->where(
                    $qb->expr()->eq(
                        'uid',
                        $qb->createNamedParameter((int)$processedData['data']['fe_user'], PDO::PARAM_INT)
                    )
                )
                ->execute()
                ->fetchAllAssociative();

            if (count($userResults) && $processedData['data']['overrides2']) {
                $userResults[0]['responsibilities'] = $processedData['data']['text'];
            }

            if (count($userResults) && $processedData['data']['overrides3']) {
                $userUids = GeneralUtility::trimExplode(',', $processedData['data']['fe_users'], true);
                $userResults[0]['representative'] = $userUids[0] ?? '';
                $userResults[0]['representative2'] = $userUids[1] ?? '';
            }
        }

        // map query response to model
        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $users = $dataMapper->map(User::class, $userResults ?? []);

        // overrides from user table content element
        if ($processorConfiguration['type'] === 'usertable' && count($users) === 1) {
            // override of user contacts
            if ($processedData['data']['overrides']) {
                $selectedContacts = GeneralUtility::intExplode(',', $processedData['data']['contacts'], true);
                foreach ($users[0]?->getContacts() ?? [] as $contact) {
                    if (!in_array($contact->getUid(), $selectedContacts)) {
                        $users[0]->removeContact($contact);
                    }
                }
            }

            // override of responsibilities
            if ($processedData['data']['overrides2']) {
                $users[0]->setResponsibilities($processedData['data']['text'] ?? '');
            }
        }

        $processedData[$processorConfiguration['as']] = $users;
        return $processedData;
    }
}
