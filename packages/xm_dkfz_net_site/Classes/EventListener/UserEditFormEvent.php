<?php

namespace Xima\XmDkfzNetSite\EventListener;


use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

class UserEditFormEvent
{

    public function __invoke(\Blueways\BwGuild\Event\UserEditFormEvent $event): void
    {
        $user = $event->getUser();

        $additionalViewData = [];
        $additionalViewData['representatives'] = $this->getUserRepresentativeAutocompleteData($user);

        $event->setAdditionalViewData($additionalViewData);
    }

    protected function getUserRepresentativeAutocompleteData(FrontendUser $user): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $users = $qb->select('uid', 'title', 'first_name', 'last_name')
            ->from('fe_users')
            ->execute()
            ->fetchAllAssociative();

        $users = array_map(function($user) {
            $userTitle = $user['title'] ? $user['title'] . ' ' : '';
            return [
                'label' => $userTitle . $user['last_name'] . ', ' . $user['first_name'],
                'value' => $user['uid']
            ];
        }, $users);

        return $users;
    }
}
