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
        $dailyLogs = $this->dataProviderService->getUnparsedDailyJsonLogs();
        $mappings = $this->mappingRepository->getAllPageAndActionMappings();

        foreach ($dailyLogs as $log) {

            $data = ['tx_xmgoaccess_domain_model_request' => []];
            $timestamp = $this->dataProviderService::getTimestampFromLogDate($log['general']->start_date);

            foreach ($mappings as $key => $mapping) {

                $data['tx_xmgoaccess_domain_model_request']['NEW-' . $key] = [
                    'pid' => 0,
                    'date' => $timestamp,
                    'mapping' => $mapping['uid'],
                    'hits' => 0,
                    'visitors' => 0,
                ];

                foreach ($log['requests']->data as $key2 => $pathData) {
                    // check regex
                    if ($mapping['regex'] === 1) {
                        preg_match('/' . $mapping['path'] . '/', $pathData->data, $matches);
                        if (!count($matches)) {
                            continue;
                        }
                    }

                    // check path 1:1
                    if ($mapping['regex'] === 0 && $mapping['path'] !== $pathData->data) {
                        continue;
                    }

                    $data['tx_xmgoaccess_domain_model_request']['NEW-' . $key]['hits'] += $pathData->hits->count;
                    $data['tx_xmgoaccess_domain_model_request']['NEW-' . $key]['visitors'] += $pathData->visitors->count;
                }
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