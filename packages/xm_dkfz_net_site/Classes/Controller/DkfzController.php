<?php

namespace Xima\XmDkfzNetSite\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
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

    public function indexAction(): ResponseInterface
    {
        $this->phoneBookUtility->loadJson();
        $entries = $this->phoneBookUtility->getPhoneBookEntries();
        $this->view->assign('entries', $entries);

        $this->phoneBookUtility->setFilterEntriesForPlaces(true );
        $this->phoneBookUtility->loadJson();
        $places = $this->phoneBookUtility->getPhoneBookEntries();
        $this->view->assign('places', $places);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

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

        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
