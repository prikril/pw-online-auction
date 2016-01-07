<?php

/**
 * @covers Auction
 * @uses Money
 * @uses Currency
 * @uses User
 */
class AuctionTest extends PHPUnit_Framework_TestCase
{
    use CreateMoneyTrait;
    use CreateAuctionTrait;

    private $title;
    private $description;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var User
     */
    private $bidder;

    /**
     * @var Money
     */
    private $startPrice;

    /**
     * @var DateTimeImmutable
     */
    private $startTime;

    /**
     * @var DateTimeImmutable
     */
    private $endTime;


    public function setUp() {
        $this->title = "myAuction1";
        $this->description = "Some rubbish from my garage.";
        $this->owner = new User("User01", "test01@test.tt");
        $this->bidder = new User("Bidder01", "bidder01@test.tt");
        $this->startPrice = new Money(1, new Currency('EUR'));
        $this->startTime = new DateTimeImmutable();
        $this->endTime = $this->startTime->add(new DateInterval("PT2H"));
    }


    public function testOwnerCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($auction->owner()->equals($this->owner));
    }

    public function testTitleCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($auction->title() === $this->title);
    }

    public function testDescriptionCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($auction->description() === $this->description);
    }

    public function testStartPriceCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($this->startPrice->equals($auction->startPrice()));
    }

    public function testCurrentPriceCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($this->startPrice->equals($auction->currentPrice()));
    }

    public function testStartTimeCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($this->startTime === $auction->startTime());
    }

    public function testEndTimeCanBeRetrieved()
    {
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $this->assertTrue($this->endTime === $auction->endTime());
    }

    public function testCanAddBid()
    {
        $bidPrice = new Money(2, new Currency('EUR'));
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);
        $auction->addBid($bidPrice, $this->bidder);

        $this->assertTrue($bidPrice->equals($auction->currentPrice()));
    }

    public function testCanAddHigherBid()
    {
        $firstBidPrice = new Money(2, new Currency('EUR'));
        $secondBidPrice = new Money(3, new Currency('EUR'));
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);

        $auction->addBid($firstBidPrice, $this->bidder);
        $auction->addBid($secondBidPrice, $this->bidder);

        $this->assertTrue($secondBidPrice->equals($auction->currentPrice()));
    }

    public function testWillNotAddLowerBid()
    {
        $firstBidPrice = new Money(3, new Currency('EUR'));
        $secondBidPrice = new Money(2, new Currency('EUR'));
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);

        $auction->addBid($firstBidPrice, $this->bidder);

        $this->setExpectedException(InvalidArgumentException::class, "Your bid must be higher than the current");
        $auction->addBid($secondBidPrice, $this->bidder);
    }

    public function testWillNotAddSameBid()
    {
        $firstBidPrice = new Money(2, new Currency('EUR'));
        $secondBidPrice = new Money(2, new Currency('EUR'));
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);

        $auction->addBid($firstBidPrice, $this->bidder);

        $this->setExpectedException(InvalidArgumentException::class, "Your bid must be higher than the current");
        $auction->addBid($secondBidPrice, $this->bidder);

    }

    public function testOwnerWillNotAddBid()
    {
        $bidPrice = new Money(2, new Currency('EUR'));
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $this->startTime, $this->endTime);

        $this->setExpectedException(InvalidArgumentException::class, "You may not bid on your own auction");
        $auction->addBid($bidPrice, $this->owner);

    }

    public function testWillNotAddBidOnEndedAuction()
    {
        $bidPrice = new Money(2, new Currency('EUR'));
        $now = new DateTimeImmutable();
        $startTime = $now->sub(new DateInterval("PT4H"));
        $endTime = $now->sub(new DateInterval("PT2H"));
        $auction = $this->createAuction($this->title, $this->description, $this->startPrice, $this->owner,
            $startTime, $endTime);

        $this->setExpectedException(LogicException::class, "You may not bid on a ended auction");
        $auction->addBid($bidPrice, $this->bidder);

    }
}
