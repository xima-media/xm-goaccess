<?php

namespace Xima\XmDkfzNetSite\EventListener;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use Xima\XmDkfzNetSite\Domain\Model\User;
use Xima\XmDkfzNetSite\Domain\Repository\NewsRepository;

class UserInfoApiEvent
{
    protected NewsRepository $newsRepository;

    protected DataMapper $dataMapper;

    public function __construct(NewsRepository $newsRepository, DataMapper $dataMapper)
    {
        $this->newsRepository = $newsRepository;
        $this->dataMapper = $dataMapper;
    }

    public function __invoke(\Blueways\BwGuild\Event\UserInfoApiEvent $event): void
    {
        $userinfo = $event->getUserinfo();

        $bookmarks = $userinfo->bookmarks;

        // add bread crumb for pages
        foreach ($bookmarks['pages'] ?? [] as $key => $page) {
            $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $page['uid'])->get();
            $bookmarks['pages'][$key]['rootline'] = array_reverse($rootline, true);
        }

        // add news image relation
        if (isset($bookmarks['tx_news_domain_model_news']) && $bookmarks['tx_news_domain_model_news']) {
            $newsUids = array_keys($bookmarks['tx_news_domain_model_news']);
            $sysFileReferences = $this->newsRepository->getPreviewSysFileReferences($newsUids);
            foreach ($sysFileReferences as $newsUid => $referenceUid) {
                $bookmarks['tx_news_domain_model_news'][$newsUid]['image'] = $referenceUid;
            }
        }

        // add user mapping
        if (isset($bookmarks['fe_users']) && $bookmarks['fe_users']) {
            $users = $this->dataMapper->map(User::class, $bookmarks['fe_users']);
            $bookmarks['users'] = $users;
        }

        $userinfo->bookmarks = $bookmarks;
        $event->setUserinfo($userinfo);
    }
}
