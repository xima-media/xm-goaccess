<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Xima\XmDkfzNetSite\Service\SvgService;

class SvgInlineViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    protected ImageService $imageService;

    public function __construct()
    {
        $this->imageService = GeneralUtility::makeInstance(ImageService::class);
    }

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('src', 'string', 'A path to a file', true);
        $this->registerArgument('id', 'string', 'Id to set in the svg');
        $this->registerArgument('class', 'string', 'Css class(es) for the svg');
        $this->registerArgument('width', 'string', 'Width of the svg.');
        $this->registerArgument('height', 'string', 'Height of the svg.');
        $this->registerArgument('viewBox', 'string', 'Specifies the view box for the svg');
        $this->registerArgument('data', 'array', 'Array of data-attributes');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function render(): string
    {
        if ((string)$this->arguments['src'] === '') {
            throw new \Exception('You must specify a string src.', 1630054037);
        }

        $image = $this->imageService->getImage($this->arguments['src'], null, false);
        if ($image->getExtension() !== 'svg') {
            throw new \Exception('You must provide a svg file.', 1630401474);
        }

        $svgContent = $image->getContents();
        if ($svgContent === '') {
            throw new \Exception('The svg file must not be empty.', 1630401503);
        }

        $attributes = [
            'id' => $this->arguments['id'],
            'class' => $this->arguments['class'],
            'width' => $this->arguments['width'],
            'height' => $this->arguments['height'],
            'viewBox' => $this->arguments['viewBox'],
            'data' => $this->arguments['data'],
        ];

        /** @var SvgService $svgService */
        $svgService = GeneralUtility::makeInstance(SvgService::class);
        return $svgService->getInlineSvg($svgContent, $attributes);
    }
}
