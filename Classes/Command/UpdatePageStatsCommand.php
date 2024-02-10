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

class UpdatePageStatsCommand extends Command
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
        $this->setDescription('Looks through daily goacces logs and creates page stats');
        $this->setHelp('');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dailyLogs = $this->dataProviderService->getDailyJsonLogs();
        $mappings = $this->mappingRepository->getAllPageMappings();

        foreach ($dailyLogs as $log) {

            $data = ['tx_xmgoaccess_domain_model_request' => []];
            $timestamp = $this->dataProviderService::getTimestampFromLogDate($log['general']->start_date);

            foreach ($log['requests']->data as $key => $pathData) {
                foreach ($mappings as $key2 => $mapping) {
                    if ($mapping['path'] === $pathData->data) {
                        $data['tx_xmgoaccess_domain_model_request']['NEW' . $key .'-' . $key2] = [
                            'pid' => 0,
                            'date' => $timestamp,
                            'page' => $mapping['page'],
                            'hits' => $pathData->hits->count,
                            'visitors' => $pathData->visitors->count,
                        ];
                    }
                }
            }

            if (!count($data['tx_xmgoaccess_domain_model_request'])) {
                continue;
            }

            // persist data
            Bootstrap::initializeBackendAuthentication();
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start($data, []);
            $dataHandler->process_datamap();
        }

        return Command::SUCCESS;
    }
}