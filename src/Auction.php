<?php declare(strict_types = 1);

class Auction
{
    private $title;
    private $description;

    /**
     * @var Money
     */
    private $startPrice;

    /**
     * @var Money
     */
    private $currentPrice;

    private $bids;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var DateTimeImmutable
     */
    private $startTime;

    /**
     * @var DateTimeImmutable
     */
    private $endTime;


    /**
     * Auction constructor.
     * @param $title
     * @param $description
     * @param Money $startPrice
     * @param User $owner
     * @param DateTimeImmutable $startTime
     * @param DateTimeImmutable $endTime
     */
    public function __construct($title, $description, Money $startPrice, User $owner,
                                DateTimeImmutable $startTime, DateTimeImmutable $endTime)
    {
        $this->title = $title;
        $this->description = $description;
        $this->startPrice = $startPrice;
        $this->currentPrice = $startPrice;
        $this->owner = $owner;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->running = true;
    }

    public function  title() {
        return $this->title;
    }

    public function  description() {
        return $this->description;
    }

    /**
     * @return Money
     */
    public function currentPrice() :Money
    {
        return $this->currentPrice;
    }

    /**
     * @return User
     */
    public function owner() :User
    {
        return $this->owner;
    }

    /**
     * @return Money
     */
    public function startPrice() :Money
    {
        return $this->startPrice;
    }

    /**
     * @return DateTimeImmutable
     */
    public function startTime() :DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * @return DateTimeImmutable
     */
    public function endTime() :DateTimeImmutable
    {
        return $this->endTime;
    }

    public function wasEnded() :bool
    {
        $now = new DateTimeImmutable();
        return $this->endTime() < $now;
    }


    /**
     * @param Money $price
     * @param User $bidder
     */
    public function addBid(Money $price, User $bidder) {
        if ($this->owner->equals($bidder)) {
            throw new InvalidArgumentException("You may not bid on your own auction");
        }

        if (!$this->currentPrice->isLessThan($price)) {
            throw new InvalidArgumentException("Your bid must be higher than the current");
        }

        if ($this->wasEnded()) {
            throw new LogicException("You may not bid on a ended auction");
        }

        $this->currentPrice = $price;
    }
}
