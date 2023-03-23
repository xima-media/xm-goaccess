<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;

class RequestsListDataProvider extends AbstractGoaccessDataProvider implements ListDataProviderInterface
{
    public function getItems(): array
    {
        $data = $this->readJsonData();
        $ignorePaths = $this->mappingRepository->getIgnoredPaths();

        $pagePaths = [];

        foreach ($data['requests']->data as $pathData) {
            $path = $pathData->data;

            foreach ($ignorePaths as $ignorePath) {
                if ($ignorePath['regex']) {
                    preg_match('/' . $ignorePath['path'] . '/', $path, $matches);
                    if ($matches) {
                        continue 2;
                    }
                } else {
                    if ($path === $ignorePath['path']) {
                        continue 2;
                    }
                }
            }

            $pagePaths[] = [
                'hits' => $pathData->hits->count,
                'visitors' => $pathData->visitors->count,
                'path' => $path,
            ];
        }

        return $pagePaths;
    }
}
