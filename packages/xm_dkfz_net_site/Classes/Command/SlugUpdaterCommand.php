<?php

namespace Xima\XmDkfzNetSite\Command;

use Doctrine\DBAL\Result;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SlugUpdaterCommand extends Command
{
    protected ConnectionPool $connectionPool;

    private const TABLES = [
        'fe_users' => 'slug',
    ];

    public function __construct(ConnectionPool $connectionPool, string $name = null)
    {
        parent::__construct($name);
        $this->connectionPool = $connectionPool;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (self::TABLES as $table => $slugField) {
            $elements = $this->getElementsForTable($table);
            if (!$elements instanceof Result || !$elements->rowCount()) {
                continue;
            }

            // init SlugHelper for this table
            $fieldConfig = $GLOBALS['TCA'][$table]['columns'][$slugField]['config'];
            /** @var SlugHelper $slugHelper */
            $slugHelper = GeneralUtility::makeInstance(
                SlugHelper::class,
                $table,
                $slugField,
                $fieldConfig
            );

            // init QueryBuilder
            $connection = $this->connectionPool->getConnectionForTable($table);
            $queryBuilder = $connection->createQueryBuilder();

            while ($record = $elements->fetchAssociative()) {
                if (!is_int($record['pid']) || !is_int($record['uid'])) {
                    continue;
                }

                // generate unique slug for record
                $value = $slugHelper->generate($record, $record['pid']);
                $state = RecordStateFactory::forName($table)
                    ->fromArray($record, $record['pid'], $record['uid']);
                $slug = $slugHelper->buildSlugForUniqueInPid($value, $state);

                // update slug field of record
                $queryBuilder->update($table)
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                        )
                    )
                    ->set($slugField, $slug);
                $queryBuilder->execute();
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $table
     * @return \Doctrine\DBAL\Result|int
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getElementsForTable(string $table): int|\Doctrine\DBAL\Result
    {
        $connection = $this->connectionPool->getConnectionForTable($table);
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder->select('*')
            ->from($table)
            ->execute();
    }
}
