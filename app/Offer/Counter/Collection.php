<?php

namespace App\Offer\Counter;

use Iterator;

class Collection implements OfferCollectionInterface
{
    private OfferIterator $offers;

    public function __construct(array $offers)
    {
        $this->setIterator($offers);
    }

    public function get(int $index): ?OfferInterface
    {
        return $this->offers->get($index) ?? null;
    }

    public function getIterator(): Iterator
    {
        return $this->offers;
    }

    public function add(Offer $offer): void
    {
        $this->offers->add($offer);
    }

    public function getOffersByRange(float $lowerPrice, float $higherPrice): int
    {
        $counter = [];
        foreach ($this->offers as $offer) {
            if (!$this->isBetweenPriceRange($offer, $lowerPrice, $higherPrice)) {
                continue;
            }
            $counter[] = $offer;
        }

        return count($counter);
    }

    public function getOffersByVendorId(int $vendorId): int
    {
        $counter = [];
        foreach ($this->offers as $offer) {
            if (!$this->belongsTo($vendorId, $offer)) {
                continue;
            }
            $counter[] = $offer;
        }

        return count($counter);
    }

    private function isBetweenPriceRange(OfferInterface $offer, float $lowerPrice, float $higherPrice): bool
    {
        return $offer->getPrice() >= $lowerPrice && $offer->getPrice() <= $higherPrice;
    }

    private function belongsTo(int $vendorId, OfferInterface $offer): bool
    {
        return $offer->getVendorId() === $vendorId;
    }

    private function setIterator(array $offers): void
    {
        $this->offers = new OfferIterator($offers);
    }
}