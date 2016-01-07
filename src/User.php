<?php

/**
 * Created by PhpStorm.
 * User: tester
 * Date: 04.12.15
 * Time: 13:37
 */
class User
{

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $mailAddress;

    /**
     * User constructor.
     * @param $nickname
     * @param $mailAddress
     */
    public function __construct($nickname, $mailAddress)
    {
        $this->nickname = $nickname;
        $this->mailAddress = $mailAddress;
    }

    public function nickname() {
        return $this->nickname;
    }

    public function mailAddress()
    {
        return $this->mailAddress;
    }

    public function equals(User $user2) {
        return $this->nickname === $user2->nickname();
    }

}