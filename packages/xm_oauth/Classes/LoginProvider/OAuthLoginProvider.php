<?php

declare(strict_types=1);

namespace Xima\XmOauth\LoginProvider;

use Xima\XmOauth\ResourceServer\Registry;
use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Backend\LoginProvider\LoginProviderInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class OAuth2LoginProvider
 * @author Christian Spoo <cs@marketing-factory.de>
 */
class OAuthLoginProvider implements LoginProviderInterface
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
        array_unshift($layoutPaths, GeneralUtility::getFileAbsFileName('EXT:xm_oauth/Resources/Private/Layouts'));
        $view->setLayoutRootPaths($layoutPaths);

        $view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:xm_oauth/Resources/Private/Templates/OAuthLogin.html')
        );

        $view->assign('providers', Registry::getAvailableResourceServers());

        if (!empty(GeneralUtility::_GP('state'))) {
            $view->assign('hasOAuthLoginError', true);
        }
    }
}
