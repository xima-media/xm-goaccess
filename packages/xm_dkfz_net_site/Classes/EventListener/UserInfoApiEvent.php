<?php

namespace Xima\XmDkfzNetSite\EventListener;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class UserInfoApiEvent
{
    public function __invoke(\Blueways\BwGuild\Event\UserInfoApiEvent $event): void
    {
        $userinfo = $event->getUserinfo();

        $bookmarks = $userinfo->bookmarks;

        foreach ($bookmarks['pages'] ?? [] as $key => $page) {
            $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $page['uid'])->get();
            $bookmarks['pages'][$key]['rootline'] = array_reverse($rootline, true);
        }

        $userinfo->bookmarks = $bookmarks;
        $event->setUserinfo($userinfo);
    }
}
