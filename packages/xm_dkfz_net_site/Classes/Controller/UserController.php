<?php

namespace Xima\XmDkfzNetSite\Controller;

use Doctrine\DBAL\DBALException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmDkfzNetSite\Domain\Repository\PlaceRepository;

class UserController extends ActionController
{
    public function __construct(protected PlaceRepository $placeRepository)
    {
    }

    /**
     * @throws DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function listPlaceResultAction(): ResponseInterface
    {
        $body = $this->request->getParsedBody();

        if (!isset($body['tx_bwguild_userlist']['demand'])) {
            return $this->htmlResponse('');
        }

        // check for demand
        $demand = array_filter($body['tx_bwguild_userlist']['demand']);
        if (empty($demand)) {
            return $this->htmlResponse('');
        }

        // abort if more fields are searched
        $otherDemand = array_filter($demand, function ($keyName) { return !in_array($keyName, ['search', 'feGroup']); }, ARRAY_FILTER_USE_KEY);
        if (!empty($otherDemand)) {
            return $this->htmlResponse('');
        }

        $places = $this->placeRepository->findByDemandArray($demand);
        if (!count($places)) {
            return $this->htmlResponse('');
        }

        $this->view->assign('places', $places);
        $html = $this->view->render();
        return $this->htmlResponse($html);
    }
}
