<?php

namespace Xima\XmDkfzNetSite\DataProcessing;

use PDO;
use TYPO3\CMS\Core\Context\Context;
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

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $userResults = $qb->select('*')
            ->from('fe_users')
            ->where($qb->expr()->neq('fe_users.last_name', $qb->createNamedParameter('')))
            ->orderBy('crdate', 'DESC')
            ->setMaxResults($maxItems)
            ->execute()
            ->fetchAllAssociative();

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $users = $dataMapper->map(User::class, $userResults);

        /** @var User $user */
        foreach ($users as $user) {
            $crdate = self::getUniqueTimestamp($user->getCrdate(), $welcomes);
            $welcomes[$crdate] = [
                'type' => 'user',
                'object' => $user,
            ];
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $languageId = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();
        $newsResults = $qb->select('*')
            ->from('tx_news_domain_model_news', 'n')
            ->innerJoin(
                'n',
                'sys_category_record_mm',
                'mm',
                $qb->expr()->eq('mm.uid_foreign', $qb->quoteIdentifier('n.uid'))
            )
            ->where($qb->expr()->eq('mm.uid_local', $qb->createNamedParameter(27, PDO::PARAM_INT)))
            ->andWhere($qb->expr()->eq('sys_language_uid', $languageId))
            ->orderBy('n.datetime', 'DESC')
            ->setMaxResults($maxItems)
            ->execute()
            ->fetchAllAssociative();

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $news = $dataMapper->map(NewsWelcomeUser::class, $newsResults);

        /** @var NewsWelcomeUser $newsItem */
        foreach ($news as $newsItem) {
            $crdate = self::getUniqueTimestamp($newsItem->getDatetime()?->getTimestamp() ?? 0, $welcomes);
            $welcomes[$crdate] = [
                'type' => 'news',
                'object' => $newsItem,
            ];
        }

        // sort by timestamp + apply cut
        ksort($welcomes);
        $welcomes = array_slice(array_reverse($welcomes), 0, $maxItems);

        $processedData[$processorConfiguration['as']] = $welcomes;
        return $processedData;
    }

    /**
     * @param mixed[] $welcomes
     */
    private static function getUniqueTimestamp(int $crdate, array $welcomes): int
    {
        if (!isset($welcomes[$crdate])) {
            return $crdate;
        }
        return self::getUniqueTimestamp($crdate + 1, $welcomes);
    }
}
