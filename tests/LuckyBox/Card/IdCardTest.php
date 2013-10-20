<?php

namespace LuckyBox\Tests\Card;

use LuckyBox\Card\IdCard;

class IdCardTest extends \PHPUnit_Framework_TestCase
{

    public function testSetId()
    {
        $card = new IdCard();
        $rp = new \ReflectionProperty($card, 'id');
        $rp->setAccessible(true);
        $this->assertNull($rp->getValue($card));
        $card->setId(10);
        $this->assertEquals(10, $rp->getValue($card));
    }

    public function testGetId()
    {
        $card = new IdCard();
        $rp = new \ReflectionProperty($card, 'id');
        $rp->setAccessible(true);
        $this->assertNull($card->getId());
        $rp->setValue($card, 10);
        $this->assertEquals(10, $card->getId());
    }

    public function testSetRate()
    {
        $card = new IdCard();
        $rp = new \ReflectionProperty($card, 'rate');
        $rp->setAccessible(true);
        $this->assertNull($rp->getValue($card));
        $card->setRate(10);
        $this->assertEquals(10, $rp->getValue($card));
    }

    public function testGetRate()
    {
        $card = new IdCard();
        $rp = new \ReflectionProperty($card, 'rate');
        $rp->setAccessible(true);
        $this->assertNull($card->getRate());
        $rp->setValue($card, 10);
        $this->assertEquals(10, $card->getRate());
    }

}
