<?php

trait CreateMoneyTrait
{
    private function createMoney()
    {
        return new Money(rand(1, 10), new Currency('EUR'));
    }
}
