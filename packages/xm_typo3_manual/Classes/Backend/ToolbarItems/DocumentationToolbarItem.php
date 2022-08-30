<?php


namespace Xima\XmTypo3Manual\Backend\ToolbarItems;


use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class DocumentationToolbarItem implements ToolbarItemInterface
{
    /**
     * @var ExtensionConfiguration
     */
    protected $extensionConfiguration;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }


    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return bool TRUE if user has access, FALSE if not
     */
    public function checkAccess()
    {
        if(!array_key_exists('enableManual', $this->getBackendUser()->getTSConfig()['options.'])) {
            return true;
        }

        return (bool)($this->getBackendUser()->getTSConfig()['options.']['enableManual'] ?? false);
    }

    /**
     * Returns the current BE user.
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Render "item" part of this toolbar
     *
     * @return string Toolbar item HTML
     */
    public function getItem()
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:xm_typo3_manual'
            . '/Resources/Private/Backend/Templates/ToolbarItems/DocumentationToolbarItem.html'));

        return $view->render();
    }

    /**
     * TRUE if this toolbar item has a collapsible drop down
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return true;
    }

    /**
     * Render "drop down" part of this toolbar
     *
     * @return string Drop down HTML
     */
    public function getDropDown()
    {
        return $this->getDocumentationToolbarItemDropDownView()->render();
    }

    /**
     * Returns an array with additional attributes added to containing <li> tag of the item.
     *
     * Typical usages are additional css classes and data-* attributes, classes may be merged
     * with other classes needed by the framework. Do NOT set an id attribute here.
     *
     * array(
     *     'class' => 'my-class',
     *     'data-foo' => '42',
     * )
     *
     * @return array List item HTML attributes
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * Returns an integer between 0 and 100 to determine
     * the position of this item relative to others
     *
     * By default, extensions should return 50 to be sorted between main core
     * items and other items that should be on the very right.
     *
     * @return int 0 .. 100
     */
    public function getIndex()
    {
        return 10;
    }

    /**
     * @return object|StandaloneView
     */
    protected function getDocumentationToolbarItemDropDownView()
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:xm_typo3_manual'
            . '/Resources/Private/Backend/Templates/ToolbarItems/DocumentationToolbarItemDropDown.html'));

        /** @var ResourceFactory $resourceFactory */
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        try {
            $files = [];
            $identifierKeys = [
                'fileidentifiers/manual-html',
                'fileidentifiers/manual-pdf',
            ];
            foreach ($identifierKeys as $key) {
                $identifier = $this->extensionConfiguration->get('xm_typo3_manual', $key);
                if (!empty($identifier)) {
                    $files[] = $resourceFactory->getFileObjectFromCombinedIdentifier($identifier);
                }
            }
            $view->assignMultiple([
                'files' => $files,
            ]);
        } catch (\Exception $e) {
            // files were not found
        }

        return $view;
    }
}
