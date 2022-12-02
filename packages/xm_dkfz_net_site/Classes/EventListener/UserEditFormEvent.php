<?php

namespace Xima\XmDkfzNetSite\EventListener;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

class UserEditFormEvent
{
    public function __invoke(\Blueways\BwGuild\Event\UserEditFormEvent $event): void
    {
        $user = $event->getUser();

        try {
            $representatives = $this->getUserRepresentativeAutocompleteData($user);
            $committees = $this->getCommittees();
        } catch (Exception|DBALException) {
        }

        $additionalViewData = [];
        $additionalViewData['representatives'] = $representatives ?? [];
        $additionalViewData['committees'] = $committees ?? [];

        $event->setAdditionalViewData($additionalViewData);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getUserRepresentativeAutocompleteData(FrontendUser $user): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $users = $qb->select('uid', 'title', 'first_name', 'last_name')
            ->from('fe_users')
            ->execute()
            ->fetchAllAssociative();

        return array_map(function ($user) {
            $userTitle = $user['title'] ? $user['title'] . ' ' : '';
            return [
                'label' => $userTitle . $user['last_name'] . ', ' . $user['first_name'],
                'value' => $user['uid'],
            ];
        }, $users);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getCommittees(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_committee');
        return $qb->select('uid', 'name')
            ->from('tx_xmdkfznetsite_domain_model_committee')
            ->execute()
            ->fetchAllKeyValue();
    }
}
