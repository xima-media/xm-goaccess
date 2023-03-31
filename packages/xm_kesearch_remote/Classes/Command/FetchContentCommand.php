<?php

namespace Xima\XmKesearchRemote\Command;

use ArrayIterator;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmKesearchRemote\Crawler\CrawlerInterface;
use Xima\XmKesearchRemote\Domain\Model\Dto\SitemapLink;

class FetchContentCommand extends Command
{
    protected string $cacheDir = '';

    protected CrawlerInterface $crawler;

    /**
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws \Exception
     */
    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        string $name = null
    ) {
        parent::__construct($name);

        $cacheDirSetting = $extensionConfiguration->get('xm_kesearch_remote', 'cache_dir');
        $cacheDirPath = realpath(Environment::getPublicPath() . '/' . $cacheDirSetting);
        if (!is_string($cacheDirPath)) {
            throw new \Exception('Not a valid cache dir "' . $cacheDirPath . '"', 1662710676);
        }
        if ($cacheDirPath && !is_dir($cacheDirPath)) {
            mkdir($cacheDirPath);
        }
        if (!is_writable($cacheDirPath)) {
            throw new \Exception('Cache dir "' . $cacheDirPath . '" is not writable', 1662710675);
        }
        $this->cacheDir = $cacheDirPath;
    }

    protected function configure(): void
    {
        $this->setDescription('Fetch and cache remote content for indexing');
        $this->setHelp('');
    }

    /**
     * @throws Exception
     * @throws DBALException
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $sitemapConfigs = $this->getSitemapConfigurationFromIndexerConfigurations();

        foreach ($sitemapConfigs as $config) {
            $crawler = $this->getCrawler($config['tx_xmkesearchremote_crawler'] ?? '');
            $xml = $this->fetchRemoteSitemap($config['tx_xmkesearchremote_sitemap']);

            $links = $crawler->convertXmlToLinks($xml, $config);

            $links = $this->filterLinksByCache($links);
            $this->fetchAndPersistLinks($links, $config['tx_xmkesearchremote_filter']);
        }

        return Command::SUCCESS;
    }

    /**
     * @throws \Exception
     */
    protected function getCrawler(string $crawlerClassName): CrawlerInterface
    {
        $crawler = GeneralUtility::makeInstance($crawlerClassName);

        if (!class_exists($crawlerClassName)) {
            throw new \Exception('Could not find class "' . $crawlerClassName . '"', 1680163092);
        }

        if (!$crawler instanceof CrawlerInterface) {
            throw new \Exception('Crawler musst implement "Xima\XmKesearchRemote\Crawler\CrawlerInterface".', 1680162954);
        }

        return $crawler;
    }

    /**
     * @param SitemapLink[] $links
     */
    protected function fetchAndPersistLinks(array $links, string $filter = 'body'): void
    {
        $client = new Client(['verify' => false]);
        foreach ($links as $link) {
            try {
                $response = $client->request('GET', $link->loc);
                $dom = $response->getBody()->getContents();
                $crawler = new Crawler($dom);
                $crawler = $crawler->filter('head title');
                $link->title = $crawler->html('');
                $crawler = new Crawler($dom);
                $crawler = $crawler->filter($filter);
                $link->content = preg_replace('/\s*\R\s*/', ' ', (trim(strip_tags($crawler->html(''))))) ?? '';
                $this->persistLink($link);
            } catch (GuzzleException $e) {
            }
        }
    }

    protected function persistLink(SitemapLink $link): void
    {
        $filename = $this->cacheDir . '/' . $link->getCacheIdentifier();
        $fileContent = $link->getFileContent();
        file_put_contents($filename, $fileContent);
    }

    /**
     * @param SitemapLink[] $links
     * @return SitemapLink[]
     */
    protected function filterLinksByCache(array $links): array
    {
        $nowTimestamp = (new \DateTime())->getTimestamp();

        foreach ($links as $key => $link) {
            $filename = realpath($this->cacheDir . '/' . $link->getCacheIdentifier()) ?: '';

            if (!file_exists($filename)) {
                continue;
            }

            $fileContent = file_get_contents($filename) ?: '';
            $cachedLink = unserialize($fileContent);

            if ($cachedLink instanceof SitemapLink && $link->lastmod < $nowTimestamp) {
                unset($links[$key]);
            }
        }

        return $links;
    }

    protected function fetchRemoteSitemap(string $sitemapUrl): string
    {
        $client = new Client(['verify' => false]);

        try {
            if (str_starts_with($sitemapUrl, '/')) {
                $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
                $sites = new ArrayIterator($siteFinder->getAllSites());
                $sitemapUrl = $sites->current()->getBase() . $sitemapUrl;
            }
            $response = $client->request('GET', $sitemapUrl);
            $xml = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
        }

        return $xml ?? '';
    }

    /**
     * @return array<int, array{tx_xmkesearchremote_sitemap: string, tx_xmkesearchremote_filter: string}>
     * @throws DBALException
     * @throws Exception
     */
    protected function getSitemapConfigurationFromIndexerConfigurations(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_kesearch_indexerconfig');
        $qb->setRestrictions($qb->getRestrictions()->removeAll()->add(new HiddenRestriction()));
        $result = $qb->select('*')
            ->from('tx_kesearch_indexerconfig')
            ->where($qb->expr()->neq('tx_xmkesearchremote_sitemap', $qb->createNamedParameter('', \PDO::PARAM_STR)))
            ->execute();

        if (is_int($result)) {
            return [];
        }

        return $result->fetchAllAssociative();
    }
}
