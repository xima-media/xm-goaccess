<?php

namespace Xima\XmDkfzNetSite\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

class DkfzController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    protected PhoneBookUtility $phoneBookUtility;

    protected IconFactory $iconFactory;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        PhoneBookUtility $phoneBookUtility,
        IconFactory $iconFactory,
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->phoneBookUtility = $phoneBookUtility;
        $this->iconFactory = $iconFactory;
    }

    /**
     * @throws \TYPO3\CMS\Core\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\DBALException
     * @throws \JsonException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     */
    public function indexAction(): ResponseInterface
    {
        $this->phoneBookUtility->loadJson();
        $entries = $this->phoneBookUtility->getPhoneBookEntries();

        $this->phoneBookUtility->setFilterEntriesForPlaces(true);
        $this->phoneBookUtility->loadJson();
        $places = $this->phoneBookUtility->getPhoneBookEntries();

        $stat = $this->phoneBookUtility->getApiStat();
        $schedulerInfos = $this->getSchedulerInformation();

        $this->view->assignMultiple([
            'entries' => $entries,
            'places' => $places,
            'stat' => $stat,
            'schedulerInfos' => $schedulerInfos,
        ]);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->addButtonBar($moduleTemplate);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    private function addButtonBar(ModuleTemplate $moduleTemplate): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $beUser = $buttonBar->makeLinkButton()
            ->setHref('#backend')
            ->setTitle('Backend user')
            ->setClasses('active type-switch')
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('status-user-backend', Icon::SIZE_SMALL));
        $feUser = $buttonBar->makeLinkButton()
            ->setHref('#frontend')
            ->setTitle('Frontend user')
            ->setClasses('type-switch')
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('status-user-frontend', Icon::SIZE_SMALL));
        $places = $buttonBar->makeLinkButton()
            ->setHref('#place')
            ->setTitle('Places')
            ->setClasses('type-switch')
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('content-marker', Icon::SIZE_SMALL));
        $buttonBar->addButton($beUser, ButtonBar::BUTTON_POSITION_LEFT, 1);
        $buttonBar->addButton($feUser, ButtonBar::BUTTON_POSITION_LEFT, 1);
        $buttonBar->addButton($places, ButtonBar::BUTTON_POSITION_LEFT, 1);
    }

    /**
     * @return array<string, mixed>
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getSchedulerInformation(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_scheduler_task');
        return $qb->select('lastexecution_time', 'lastexecution_context')
            ->from('tx_scheduler_task')
            ->where('uid', $qb->createNamedParameter(6, \PDO::PARAM_INT))
            ->execute()
            ->fetchAssociative() ?: [];
    }
}
