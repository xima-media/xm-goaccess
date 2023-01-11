<?php

namespace Xima\XimaTwitterClient\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\Exception\FolderDoesNotExistException;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTwitterClient\Domain\Model\Account;
use Xima\XimaTwitterClient\Domain\Repository\AccountRepository;
use Xima\XimaTwitterClient\Domain\Repository\TweetRepository;
use Xima\XimaTwitterClient\FetchType\FetchTypeInterface;

class FetchTweetsCommand extends Command
{
    private TwitterOAuth $connection;

    public function __construct(
        private LoggerInterface $logger,
        protected ExtensionConfiguration $extensionConfiguration,
        protected AccountRepository $accountRepository,
        protected TweetRepository $tweetRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initConnection();

        $newTweets = 0;
        $accounts = $this->accountRepository->findAllIgnorePid();
        $imageFolder = $this->getImageStorage();

        /** @var Account $account */
        foreach ($accounts as $account) {
            $fetchType = GeneralUtility::makeInstance($account->getFetchType());

            if (!$fetchType instanceof FetchTypeInterface) {
                throw new \Exception('FetchType "' . $account->getFetchType() . '" does not implement FetchTypeInterface',
                    1673335140);
            }

            $fetchType->setAccount($account);
            $fetchType->setImageFolder($imageFolder);
            $fetchType->setTweetRepository($this->tweetRepository);

            $userId = $this->fetchUserId($account->getUsername());

            $newTweets = $fetchType->fetchTweets($this->connection, $userId);
        }

        $output->writeln('Added ' . $newTweets . ' new tweets');

        return Command::SUCCESS;
    }

    protected function initConnection(): void
    {
        $extConf = $this->extensionConfiguration->get('xima_twitter_client');

        $this->connection = new TwitterOAuth($extConf['api_key'], $extConf['api_secret'], $extConf['access_key'],
            $extConf['access_secret']);
        $this->connection->setApiVersion('2');
    }

    protected function fetchUserId(string $username): string
    {
        $extConf = $this->extensionConfiguration->get('xima_twitter_client');

        $connection = new TwitterOAuth($extConf['api_key'], $extConf['api_secret'], $extConf['access_key'],
            $extConf['access_secret']);
        $connection->setApiVersion('2');
        $content = $connection->get('users/by', ['usernames' => $username]);

        if (isset($content->errors) && count($content->errors)) {
            throw new \Exception($content->errors[0]->title, 1673361745);
        }

        if (!$content->data[0]->id || count($content->data) !== 1 || strtolower($username) !== strtolower($content->data[0]->username)) {
            throw new \Exception('Could not fetch twitter user id', 1673281853);
        }

        return $content->data[0]->id;
    }

    protected function fetchLatestTweets(string $userId): array
    {
        $response = $this->connection->get('users/' . $userId . '/tweets', [
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

    protected function getImageStorage(): Folder
    {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $extConf = $this->extensionConfiguration->get('xima_twitter_client');
        $path = $extConf['image_storage'];

        if (str_contains($path, ':')) {
            try {
                $folder = $resourceFactory->getFolderObjectFromCombinedIdentifier($path);
            } catch (FolderDoesNotExistException) {
                $segments = explode(':', $path);
                $folder = $resourceFactory->getStorageObject((int)$segments[0])->createFolder($segments[1]);
            }
        } else {
            try {
                $folder = $resourceFactory->getDefaultStorage()->getFolder($path);
            } catch (FolderDoesNotExistException) {
                $folder = $resourceFactory->getDefaultStorage()->createFolder($path);
            }
        }

        if (!$folder instanceof Folder) {
            throw new Exception('Could not get or create folder "' . $path . '" for twitter image download',
                1673432058);
        }

        return $folder;
    }
}
