<?php
namespace Tpwd\KeSearchPremium\Headless;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class HeadlessApi implements \TYPO3\CMS\Core\SingletonInterface {
    /**
     * @var bool
     */
    protected $headlessMode = false;

    /**
     * @return bool
     */
    public function getHeadlessMode(): bool
    {
        return $this->headlessMode;
    }

    /**
     * @param bool $headlessMode
     */
    public function setHeadlessMode(bool $headlessMode)
    {
        $this->headlessMode = $headlessMode;
    }

    public function renderResponse(int $contentElementUid): string
    {
        // make plugins cacheable - non-cached plugins would only render a "USER_INT" placeholder
        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kesearch_pi1'] = 'USER';
        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kesearch_pi2'] = 'USER';

        // set template paths for this request
        $GLOBALS['TSFE']->tmpl->setup['lib.']['contentElement.']['templateRootPaths.']
        [$this->getNewTyposcriptKey($GLOBALS['TSFE']->tmpl->setup['lib.']['contentElement.']['templateRootPaths.'])]
            = 'EXT:ke_search_premium/Resources/Private/Templates/ContentElements';

        // define content element
        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kesearchpremium.']['headlessapi.']['contentelement'] = 'RECORDS';
        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kesearchpremium.']['headlessapi.']['contentelement.'] =
            [
                'source' => $contentElementUid,
                'dontCheckPid' => 1,
                'tables' => 'tt_content'
            ];

        /** @var StandaloneView $headlessView */
        $headlessView = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $headlessView->setTemplateRootPaths(['EXT:ke_search_premium/Resources/Private/Templates/']);
        $headlessView->setTemplate('Json');
        return trim($headlessView->render());
    }

    /**
     * Removes all the fields of the result which are not in the whitelist
     *
     * @param string $responseContent
     * @param string $fieldWhitelist
     * @return string
     */
    public function applyFieldWhitelist(string $responseContent, string $fieldWhitelist): string
    {
        $responseArray = json_decode($responseContent, true);
        if ($responseArray) {
            $allowedFields = explode(';', $fieldWhitelist);
            foreach ($allowedFields as $key => $allowedField) {
                if (stristr($allowedField, ':')) {
                    list($prefix, $fieldsCommaList) = explode(':', $allowedField);
                    $fields = explode(',', $fieldsCommaList);
                    if (!empty($fields)) {
                        foreach ($fields as $field) {
                            $allowedFields[] = $prefix . '-' . $field;
                            $allowedFields[$prefix] = $fields;
                        }
                    }

                    unset($allowedFields[$key]);
                }
            }
            $this->unsetArrayKeyIfNotAllowed($responseArray, $allowedFields, '');
        }
        return json_encode($responseArray, true);
    }

    /**
     * @param array $array
     * @param array $allowedFields
     * @param string $prefix
     */
    public function unsetArrayKeyIfNotAllowed(array &$array, array &$allowedFields, string $prefix)
    {
        foreach ($array as $key => $value) {
            $combinedKey = $prefix;
            if (!is_int($key)) {
                if (!empty($combinedKey)) {
                    $combinedKey .= '-';
                }
                $combinedKey .= $key;
            }

            if (!in_array($combinedKey, $allowedFields)) {
                unset($array[$key]);
            }

            if (isset($array[$key]) && is_array($array[$key])) {
                $this->unsetArrayKeyIfNotAllowed($array[$key], $allowedFields, $combinedKey);
            }
        }
    }

    /**
     * @param $typoscriptArray
     * @return string
     */
    public function getNewTyposcriptKey($typoscriptArray):string
    {
        if (empty($typoscriptArray)) {
            $typoscriptArray = [];
        }
        $keys = array_keys($typoscriptArray);
        if (empty($keys)) {
            $newKey = '1';
        } else {
            $keysArray = [];
            foreach ($keys as $key) {
                $keysArray[] = intval($key);
            }
            $newKey = strval(max($keysArray) + 1);
        }
        return $newKey;
    }
}