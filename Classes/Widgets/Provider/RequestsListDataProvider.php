<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;

class RequestsListDataProvider extends AbstractGoaccessDataProvider implements ListDataProviderInterface
{
    public function getItems(): array
    {
        $data = $this->readJsonData();

        $pagePaths = [];

        foreach ($data['requests']->data as $pathData) {
            $path = $pathData->data;

            if (str_starts_with($path, '/typo3/')
                || $path === '/typo3'
                || str_starts_with($path, '/typo3temp/')
                || str_starts_with($path, '/server-status?auto')
                || str_starts_with($path, '/fileadmin/')
                || str_starts_with($path, '/cache_clear_random_')
                || str_starts_with($path, '/_assets/')) {
                continue;
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
