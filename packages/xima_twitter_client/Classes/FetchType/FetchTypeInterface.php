<?php

namespace Xima\XimaTwitterClient\FetchType;

use Abraham\TwitterOAuth\TwitterOAuth;
use TYPO3\CMS\Core\Resource\Folder;
use Xima\XimaTwitterClient\Domain\Model\Account;
use Xima\XimaTwitterClient\Domain\Repository\TweetRepository;

interface FetchTypeInterface
{
    public function fetchTweets(TwitterOAuth $connection, string $userId): int;

    public function setAccount(Account $account): void;

    public function setTweetRepository(TweetRepository $tweetRepository): void;

    public function setImageFolder(Folder $folder): void;
}
