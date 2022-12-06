<?php

namespace Xima\XmDkfzNetSite\EventListener;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use Xima\XmDkfzNetSite\Domain\Repository\NewsRepository;

class UserInfoApiEvent
{
    protected NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function __invoke(\Blueways\BwGuild\Event\UserInfoApiEvent $event): void
    {
        $userinfo = $event->getUserinfo();

        $bookmarks = $userinfo->bookmarks;

        foreach ($bookmarks['pages'] ?? [] as $key => $page) {
            $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $page['uid'])->get();
            $bookmarks['pages'][$key]['rootline'] = array_reverse($rootline, true);
        }

        if (isset($bookmarks['tx_news_domain_model_news']) && $bookmarks['tx_news_domain_model_news']) {
            $newsUids = array_keys($bookmarks['tx_news_domain_model_news']);
            $sysFileReferences = $this->newsRepository->getPreviewSysFileReferences($newsUids);
            foreach ($sysFileReferences as $newsUid => $referenceUid) {
                $bookmarks['tx_news_domain_model_news'][$newsUid]['image'] = $referenceUid;
            }
        }

        $userinfo->bookmarks = $bookmarks;
        $event->setUserinfo($userinfo);
    }

}
