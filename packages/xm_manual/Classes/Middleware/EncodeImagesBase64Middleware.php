<?php

namespace Xima\XmManual\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * prefix all links and src with $GLOBALS['TSFE']->absRefPrefix
 * Class GenerateAbsoluteLinksMiddleware
 */
class EncodeImagesBase64Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $params = $request->getQueryParams();
        if (
            !($response instanceof NullResponse)
            && $GLOBALS['TSFE'] instanceof TypoScriptFrontendController
            && isset($params['type'])
            && $params['type'] === '1664618986'
        ) {
            $body = $response->getBody();
            $body->rewind();
            $contents = $response->getBody()->getContents();
            $content = $this->parseRelativeToAbsoluteUrls($contents);
            $body = new Stream('php://temp', 'rw');
            $body->write($content);
            $response = $response->withBody($body);
        }

        return $response;
    }

    protected function parseRelativeToAbsoluteUrls(string $input = ''): string
    {
        $pattern = '/<img[^>]+src="([^">]+)"/';
        //preg_match_all($pattern, $input, $images);

        return preg_replace_callback($pattern, function ($img) {
            if (!is_array($img)) {
                return '';
            }

            $path = Environment::getPublicPath();
            $path .= str_starts_with($img[1], '/') ? $img[1] : '/' . $img[1];

            if (!file_exists($path)) {
                return $img[0];
            }

            $fileContent = file_get_contents($path);

            if (!$fileContent) {
                return $img[0];
            }
            $fileType = mime_content_type($path);
            $newSrc = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
            return str_replace($img[1], $newSrc, $img[0]);
        }, $input);
    }
}
