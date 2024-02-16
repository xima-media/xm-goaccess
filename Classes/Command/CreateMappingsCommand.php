<?php

namespace Xima\XmGoaccess\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmGoaccess\Domain\Repository\MappingRepository;
use Xima\XmGoaccess\Service\DataProviderService;

class CreateMappingsCommand extends Command
{
    protected MappingRepository $mappingRepository;

    protected DataProviderService $dataProviderService;

    public function __construct(
        MappingRepository $mappingRepository,
        DataProviderService $dataProvider,
        string $name = null
    ) {
        parent::__construct($name);
        $this->mappingRepository = $mappingRepository;
        $this->dataProviderService = $dataProvider;
    }

    protected function configure(): void
    {
        $this->setDescription('Looks through goaccess.json and creates page mappings');
        $this->setHelp('');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $qb->getRestrictions()->removeAll();
        $result = $qb->select('uid', 'slug', 'sys_language_uid')
            ->from('pages')
            ->execute();
        $pages = $result->fetchAllAssociative();

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_redirect');
        $qb->getRestrictions()->removeAll();
        $result = $qb->select('uid', 'source_path', 'is_regexp')
            ->from('sys_redirect')
            ->execute();
        $redirects = $result->fetchAllAssociative();

        $mappingPaths = $this->mappingRepository->getAllNonRegexPaths();
        $paths = $this->dataProviderService->readJsonData();

        $data = ['tx_xmgoaccess_domain_model_mapping' => []];

        foreach ($paths['requests']?->data as $key => $pathData) {
            $path = $pathData->data;

            // mapping already present
            if (in_array($path, $mappingPaths)) {
                continue;
            }

            foreach ($pages as $key2 => $page) {
                // no match
                if ($page['slug'] !== $pathData->data) {
                    continue;
                }

                // create mapping
                $data['tx_xmgoaccess_domain_model_mapping']['NEW' . $key . $key2] = [
                    'pid' => 0,
                    'path' => $page['slug'],
                    'record_type' => 0,
                    'page' => $page['uid'],
                ];
            }

            foreach ($redirects as $key2 => $redirect) {
                if (!$redirect['source_path'] || !is_string($redirect['source_path'])) {
                    continue;
                }

                // check regex
                if ($redirect['is_regexp']) {
                    $matchResult = preg_match($redirect['source_path'], $pathData->data, $matches);
                    if ($matchResult === 0) {
                        continue;
                    }
                }

                // no match
                if (!$redirect['is_regexp'] && $redirect['source_path'] !== $pathData->data) {
                    continue;
                }

                // create mapping
                $data['tx_xmgoaccess_domain_model_mapping']['NEW' . $key . $key2] = [
                    'pid' => 0,
                    'path' => $pathData->data,
                    'record_type' => 3,
                    'foreign_uid' => $redirect['uid'],
                    'foreign_table' => 'sys_redirect',
                ];
            }
        }

        if (!count($data['tx_xmgoaccess_domain_model_mapping'])) {
            $output->writeln('No matches found');
            return Command::SUCCESS;
        }

        Bootstrap::initializeBackendAuthentication();
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($data, []);
        $dataHandler->process_datamap();

        $output->writeln('Added ' . count($data['tx_xmgoaccess_domain_model_mapping']) . ' mappings');

        return Command::SUCCESS;
    }
}
