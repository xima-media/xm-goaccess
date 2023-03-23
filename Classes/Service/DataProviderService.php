<?php

namespace Xima\XmGoaccess\Service;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use Xima\XmGoaccess\Domain\Model\Mapping;
use Xima\XmGoaccess\Domain\Repository\MappingRepository;

class DataProviderService
{
    /**
     * @var QueryResult<Mapping>
     */
    private ?QueryResult $mappings = null;

    public function __construct(
        protected ExtensionConfiguration $extensionConfiguration,
        protected MappingRepository $mappingRepository,
        protected IconFactory $iconFactory
    ) {
    }

    /**
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function readJsonData(): array
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_goaccess');

        if (!isset($extConf['json_path']) || !$extConf['json_path']) {
            throw new \Exception('Goaccess json_path is not configured', 1662881054);
        }

        $filePath = str_starts_with($extConf['json_path'],
            '/') ? $extConf['json_path'] : Environment::getPublicPath() . '/' . $extConf['json_path'];
        if (!file_exists($filePath)) {
            throw new \Exception('File "' . $filePath . '" not found', 1662881054);
        }

        $content = file_get_contents($filePath);

        return $content ? (array)json_decode($content) : [];
    }

    public function getRequestList()
    {
        $goaccessData = $this->readJsonData();

        $this->mappings = $this->mappingRepository->findAll();

        $items = [];

        foreach ($goaccessData['requests']->data as $pathData) {
            $path = $pathData->data;

            $items[] = [
                'hits' => $pathData->hits->count,
                'visitors' => $pathData->visitors->count,
                'path' => $path,
                'mappings' => $this->resolvePathMapping($path),
            ];
        }

        return $items;
    }

    private function resolvePathMapping(string $path): array
    {
        $pathMappings = [];

        foreach ($this->mappings as $mapping) {

            if ($mapping->isRegex()) {
                preg_match('/' . $mapping->getPath() . '/', $path, $matches);
                if ($matches) {
                    $this->enrichMapping($mapping);
                    $pathMappings[] = $mapping;
                    continue;
                }
            }

            if (!$mapping->isRegex() && $path === $mapping->getPath()) {
                $this->enrichMapping($mapping);
                $pathMappings[] = $mapping;
            }
        }

        return $pathMappings;
    }

    private function enrichMapping(Mapping &$mapping): void
    {
        if ($mapping->getRecordType() === 0 && $mapping->getPage()) {
            $pageRecord = BackendUtility::readPageAccess($mapping->getPage(), '1=1');
            if (!$pageRecord) {
                return;
            }
            $iconMarkup = $this->iconFactory->getIconForRecord('pages', $pageRecord, Icon::SIZE_SMALL)->render();
            $mapping->setIconMarkup($iconMarkup);

            $pagePath = $pageRecord['title'] . ' [' .$pageRecord['uid'] .']';
            $mapping->setPagePath($pagePath);
        }
    }

}
