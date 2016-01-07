<?php

/**
 * @covers User
 * @uses Money
 * @uses Currency
 */
class UserTest extends PHPUnit_Framework_TestCase {

    /**
     * @var User
     */
    private $testUser;


    public function setUp() {
        $this->testUser = new User("User01", "test01@test.tt");
    }


    public function testNicknameCanBeRetrived()
    {
        $this->assertEquals("User01", $this->testUser->nickname());
    }

    public function testMailAddressCanBeRetrived()
    {
        $this->assertEquals("test01@test.tt", $this->testUser->mailAddress());
    }

    public function testCanCompareSameUsers()
    {
        $this->assertTrue($this->testUser->equals($this->testUser));
    }

    public function testCanCompareDifferentUsers()
    {
        $user2 = new User("User02", "test02@test.tt");

        $this->assertFalse($this->testUser->equals($user2));
    }
}
