<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Service;

class SvgService
{
    /**
     * @param string $svgContent
     * @param array $tags
     * @return string
     */
    public function getInlineSvg(
        string $svgContent,
        array $tags = []
    ): string {
        $svgElement = simplexml_load_string($svgContent);
        if (!$svgElement instanceof \SimpleXMLElement) {
            return '';
        }

        $domXml = dom_import_simplexml($svgElement);
        if (!$domXml instanceof \DOMElement || !$domXml->ownerDocument instanceof \DOMDocument) {
            return '';
        }

        $tags['id'] = htmlspecialchars(trim((string)$tags['id']));
        if ($tags['id'] !== '') {
            $domXml->setAttribute('id', $tags['id']);
        }

        $tags['class'] = htmlspecialchars(trim((string)$tags['class']));
        if ($tags['class'] !== '') {
            $domXml->setAttribute('class', $tags['class']);
        }

        if ((int)$tags['height'] > 0) {
            $domXml->setAttribute('height', (string)$tags['height']);
        }

        if ((int)$tags['width'] > 0) {
            $domXml->setAttribute('width', (string)$tags['width']);
        }

        $tags['viewBox'] = htmlspecialchars(trim((string)$tags['viewBox']));
        if ($tags['viewBox'] !== '') {
            $domXml->setAttribute('viewBox', $tags['viewBox']);
        }

        if (is_array($tags['data'])) {
            foreach ($tags['data'] as $dataAttributeKey => $dataAttributeValue) {
                $dataAttributeKey = htmlspecialchars(trim((string)$dataAttributeKey));
                $dataAttributeValue = htmlspecialchars(trim((string)$dataAttributeValue));
                if ($dataAttributeKey !== '' && $dataAttributeValue !== '') {
                    $domXml->setAttribute('data-' . $dataAttributeKey, $dataAttributeValue);
                }
            }
        }

        return (string)$domXml->ownerDocument->saveXML($domXml->ownerDocument->documentElement);
    }
}
