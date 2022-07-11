<?php

namespace Blueways\BwGuild\Domain\Repository;

/**
 * Class OfferRepository
 *
 * @package Blueways\BwGuild\Domain\Repository
 */
class OfferRepository extends AbstractDemandRepository
{

    public function getGroupedOffers()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $allOffers = $query->execute();

        $offers = [];

        foreach ($allOffers as $offer) {
            $offers[$offer->getRecordType()][] = $offer;
        }

        return $offers;
    }
}
