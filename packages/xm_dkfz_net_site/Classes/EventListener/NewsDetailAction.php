<?php

namespace Xima\XmDkfzNetSite\EventListener;

use Blueways\BwGuild\Event\InitializeUserUpdateEvent;
use GeorgRinger\News\Event\NewsDetailActionEvent;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use Xima\XmDkfzNetSite\Domain\Repository\NewsRepository;

class NewsDetailAction
{

    public function __construct(protected NewsRepository $newsRepository)
    {
    }

    public function __invoke(NewsDetailActionEvent $event): void
    {
        $values = $event->getAssignedValues();
        $uid = $values['newsItem']?->getUid() ?? 0;

        if ($uid) {
            $news = $this->newsRepository->findByUid($uid);
            $values['color'] = $news->getTxXmdkfznetsiteColor();
            $event->setAssignedValues($values);
        }
    }
}
