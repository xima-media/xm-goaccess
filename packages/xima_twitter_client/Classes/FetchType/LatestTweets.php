<?php

namespace Xima\XimaTwitterClient\FetchType;

use Abraham\TwitterOAuth\TwitterOAuth;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Utility\File\BasicFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTwitterClient\Domain\Model\Account;
use Xima\XimaTwitterClient\Domain\Repository\TweetRepository;
use Xima\XimaTwitterClient\FetchType\FetchTypeInterface;

class LatestTweets implements FetchTypeInterface
{
    protected Account $account;

    protected TweetRepository $tweetRepository;

    protected Folder $imageFolder;

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function setTweetRepository(TweetRepository $tweetRepository): void
    {
        $this->tweetRepository = $tweetRepository;
    }

    public function fetchTweets(TwitterOAuth $connection, string $userId): int
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

        $tweetsToPersist = $this->filterResponse($response);
        $this->saveTweets($tweetsToPersist);

        return 1;
    }

    protected function saveTweets($response)
    {

        $data = ['tx_ximatwitterclient_domain_model_tweet' => [], 'sys_file_reference' => []];
        foreach ($response->data as $key => $tweet) {


            foreach ($tweet?->attachments?->media_keys ?? [] as $key2 => $mediaKey) {
                $sysFileIdentifier = $this->saveAttachment($response, $mediaKey);

                if (!$sysFileIdentifier) {
                    continue;
                }

                $data['sys_file_reference']['NEW' . $key . 'FILE' . $key2] = [
                    'table_local' => 'sys_file',
                    'uid_local' => $sysFileIdentifier,
                    'tablenames' => 'tx_ximatwitterclient_domain_model_tweet',
                    'uid_foreign' => 'NEW' . $key,
                    'fieldname' => 'attachments',
                    'pid' => $this->account->getPid(),
                ];
            }

            $data['tx_ximatwitterclient_domain_model_tweet']['NEW' . $key] = [
                'id' => $tweet->id,
                'author_id' => $tweet->author_id,
                'text' => $tweet->text,
            ];
        }

        if (count($data['tx_ximatwitterclient_domain_model_tweet'])) {
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start($data, []);
            //$dataHandler->process_datamap();
        }
    }

    protected function saveAttachment($response, string $mediaKey): ?int
    {
        $uid = null;

        foreach ($response->includes->media as $media) {
            if ($media->media_key !== $mediaKey) {
                continue;
            }

            if ($media->type === 'photo') {
                $uid = $this->downloadImage($media->url);
            }

            if ($media->type === 'video') {
                $uid = $this->downloadImage($media->preview_image_url);
            }
        }

        return $uid;
    }

    protected function downloadImage(string $imageUrl): int
    {
        $filename = basename($imageUrl);

        if ($this->imageFolder->hasFile($filename)) {
            return $this->imageFolder->getFile($filename)->getUid();
        }

        $file = $this->imageFolder->createFile($filename);
        $tempFile = $file->getForLocalProcessing();
        $client = GeneralUtility::makeInstance(Client::class);
        $resource = \GuzzleHttp\Psr7\Utils::tryFopen($tempFile, 'w');
        $client->request('GET', $imageUrl, ['sink' => $resource]);
        $file->setContents(file_get_contents($tempFile));

        return $file->getUid();
    }

    protected function filterResponse($response): \stdClass
    {
        $ids = $this->getTweetIdsFromResponse($response);
        $idsToIgnore = $this->tweetRepository->findTweetsByIds($ids);

        foreach ($response->data as &$tweet) {
            if (in_array($tweet->id, $idsToIgnore)) {
                unset($tweet);
            }
        }

        return $response;
    }

    /**
     * @return string[]
     */
    public function getTweetIdsFromResponse($tweets): array
    {
        $ids = [];
        foreach ($tweets->data as $tweet) {
            $ids[] = $tweet->id;
        }
        return $ids;
    }

    public function setImageFolder(Folder $folder): void
    {
        $this->imageFolder = $folder;
    }
}
