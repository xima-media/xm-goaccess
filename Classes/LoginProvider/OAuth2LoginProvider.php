<?php

declare(strict_types=1);

namespace Mfc\OAuth2\LoginProvider;

use Mfc\OAuth2\ResourceServer\Registry;
use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Backend\LoginProvider\LoginProviderInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class OAuth2LoginProvider
 * @author Christian Spoo <cs@marketing-factory.de>
 */
class OAuth2LoginProvider implements LoginProviderInterface
{

    /**
     * Render the login HTML
     *
     * Implement this method and set the template for your form.
     * This is also the right place to assign data to the view
     * and add necessary JavaScript resources to the page renderer.
     *
     * A good example is EXT:openid
     *
     * Example:
     *    $view->setTemplatePathAndFilename($pathAndFilename);
     *    $view->assign('foo', 'bar');
     *
     * @param StandaloneView $view
     * @param PageRenderer $pageRenderer
     * @param LoginController $loginController
     */
    public function render(StandaloneView $view, PageRenderer $pageRenderer, LoginController $loginController)
    {
        $layoutPaths = $view->getLayoutRootPaths();
        array_unshift($layoutPaths, GeneralUtility::getFileAbsFileName('EXT:oauth2/Resources/Private/Layouts'));
        $view->setLayoutRootPaths($layoutPaths);

        $view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:oauth2/Resources/Private/Templates/OAuth2Login.html')
        );

        $view->assign('providers', Registry::getAvailableResourceServers());

        if (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 11000000) {
            $view->assign('baseLayout', 'OAuth2Login-11');
        } elseif (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000) {
            $view->assign('baseLayout', 'OAuth2Login-9');
        } else {
            $view->assign('baseLayout', 'OAuth2Login');
        }

        if (!empty(GeneralUtility::_GP('state'))) {
            $view->assign('hasOAuthLoginError', true);
        }
    }
}
