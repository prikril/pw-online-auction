<?php

trait CreateAuctionTrait
{
    /**
     * @param $title
     * @param $description
     * @param Money $startPrice
     * @param User $owner
     * @param DateTimeImmutable $startTime
     * @param DateTimeImmutable $endTime
     * @return Auction
     */
    private function createAuction($title, $description, Money $startPrice, User $owner,
                                   DateTimeImmutable $startTime, DateTimeImmutable $endTime)
    {
        return new Auction($title, $description, $startPrice, $owner, $startTime, $endTime);
    }
}
