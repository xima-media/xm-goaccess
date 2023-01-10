<?php

namespace Xima\XimaTwitterClient\FetchType;

use Abraham\TwitterOAuth\TwitterOAuth;
use Xima\XimaTwitterClient\Domain\Model\Account;
use Xima\XimaTwitterClient\FetchType\FetchTypeInterface;

class LatestTweets implements FetchTypeInterface
{
    protected Account $account;

    public function __constructor(Account $account)
    {
        $this->account = $account;
    }

    public function fetchTweets(TwitterOAuth $connection, string $userId): array
    {
        $response = $connection->get('users/' . $userId . '/tweets', [
            'exclude' => 'replies,retweets',
            'expansions' => 'author_id,attachments.media_keys',
            'max_results' => '10',
            'media.fields' => 'url,type,media_key,preview_image_url,alt_text',
            'user.fields' => 'name,id,profile_image_url'
        ]);

        if (!count($response->data)) {
            throw new \Exception('Could not fetch tweets', 1673286318);
        }

        return (array)$response;
    }

}
