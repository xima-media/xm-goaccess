<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use PDO;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmDkfzNetSite\Domain\Model\NewsWelcomeUser;
use Xima\XmDkfzNetSite\Domain\Model\User;

class LatestUserProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $welcomes = [];

        $maxItems = (int)$processorConfiguration['max'];

        self::addSpecialNewsItems($welcomes, $maxItems);
        $maxItems -= count($welcomes);
        self::addLatestUsers($welcomes, $maxItems);

        $processedData[$processorConfiguration['as']] = $welcomes;

        return $processedData;
    }

    /**
     * @param array<int, mixed> $welcomes
     * @param int $maxItems
     * @throws DBALException
     * @throws Exception
     * @throws AspectNotFoundException
     */
    private static function addSpecialNewsItems(array &$welcomes, int $maxItems): void
    {
        $maxAgeTimestamp = (new \DateTime())->modify('-7 days')->setTime(0, 0)->getTimestamp();

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $languageId = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();
        $query = $qb->select('*')
            ->from('tx_news_domain_model_news', 'n')
            ->innerJoin(
                'n',
                'sys_category_record_mm',
                'mm',
                $qb->expr()->eq('mm.uid_foreign', $qb->quoteIdentifier('n.uid'))
            )
            ->where($qb->expr()->eq('mm.uid_local', $qb->createNamedParameter(27, PDO::PARAM_INT)))
            ->andWhere($qb->expr()->eq('sys_language_uid', $languageId))
            ->andWhere($qb->expr()->eq('sys_language_uid', $languageId))
            ->andWhere($qb->expr()->gte('datetime', $qb->createNamedParameter($maxAgeTimestamp, PDO::PARAM_INT)))
            ->orderBy('n.datetime', 'DESC')
            ->setMaxResults($maxItems)
            ->execute();

        if (!$query instanceof Result) {
            return;
        }

        $newsResults = $query->fetchAllAssociative();

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $news = $dataMapper->map(NewsWelcomeUser::class, $newsResults);

        /** @var NewsWelcomeUser $newsItem */
        foreach ($news as $newsItem) {
            $welcomes[] = [
                'type' => 'news',
                'object' => $newsItem,
            ];
        }
    }

    /**
     * @param array<int, mixed> $welcomes
     * @param int $maxItems
     * @throws DBALException
     * @throws Exception
     */
    private static function addLatestUsers(array &$welcomes, int $maxItems): void
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $query = $qb->select('*')
            ->from('fe_users')
            ->where($qb->expr()->neq('fe_users.last_name', $qb->createNamedParameter('')))
            ->orderBy('registration_date', 'DESC')
            ->setMaxResults($maxItems)
            ->execute();

        if (!$query instanceof Result) {
            return;
        }

        $userResults = $query->fetchAllAssociative();

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $users = $dataMapper->map(User::class, $userResults);

        /** @var User $user */
        foreach ($users as $user) {
            $welcomes[] = [
                'type' => 'user',
                'object' => $user,
            ];
        }
    }
}
