<?php

namespace Xima\XmDkfzNetSite\Form\Element;

use TYPO3\CMS\Backend\Form\Element\CheckboxToggleElement;

class OverrideToggleElement extends CheckboxToggleElement
{
    public function render(): array
    {
        $resultArray = parent::render();

        $data = $this->data['parameterArray'];

        $resultArray['requireJsModules'][] = ['TYPO3/CMS/XmDkfzNetSite/OverrideToggleElement' => 'function(OverrideToggleElement) { OverrideToggleElement.init("' . $data['itemFormElName'] . '"); }'];

        return $resultArray;
    }
}
