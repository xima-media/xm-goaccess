<?php

namespace Xima\XimaTwitterClient\FetchType;

use Abraham\TwitterOAuth\TwitterOAuth;
use Xima\XimaTwitterClient\Domain\Model\Account;

interface FetchTypeInterface
{
    public function fetchTweets(TwitterOAuth $connection, string $userId): array;

    public function __constructor(Account $account);
}
