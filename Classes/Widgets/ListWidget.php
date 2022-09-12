<?php

declare(strict_types=1);

namespace Xima\XmGoaccess\Widgets;

use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ListWidget implements WidgetInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;

    /**
     * @var StandaloneView
     */
    private $view;

    /**
     * @var array
     */
    private $options;

    /**
     * @var ButtonProviderInterface|null
     */
    private $buttonProvider;

    /**
     * @var ListDataProviderInterface
     */
    private $dataProvider;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        ListDataProviderInterface $dataProvider,
        StandaloneView $view,
        $buttonProvider = null,
        array $options = []
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
        $this->options = $options;
        $this->buttonProvider = $buttonProvider;
        $this->dataProvider = $dataProvider;
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplatePathAndFilename('EXT:xm_goaccess/Resources/Private/Templates/Widget/RequestsWidget.html');
        $this->view->assignMultiple([
            'items' => $this->getItems(),
            'options' => $this->options,
            'button' => $this->buttonProvider,
            'configuration' => $this->configuration,
        ]);
        return $this->view->render();
    }

    protected function getItems(): array
    {
        return $this->dataProvider->getItems();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
