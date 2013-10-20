<?php

namespace LuckyBox\Tests;

use LuckyBox\LuckyBox;
use LuckyBox\Card\IdCard;

class LuckyBoxTest extends \PHPUnit_Framework_TestCase
{

    public function testDraw_Functional()
    {
        $card1 = new IdCard();
        $card1->setId(1);
        $card1->setRate(55);

        $card2 = new IdCard();
        $card2->setId(2);
        $card2->setRate(45);

        $box = new LuckyBox();
        $box->add($card1);
        $box->add($card2);

        for ($i = 0 ; $i < 3; $i++) {
            $result = $box->draw();
            $this->assertTrue($result === $card1 || $result === $card2);
        }

        $this->assertFalse($box->isEmpty());
        $box->clear();
        $this->assertTrue($box->isEmpty());

        $box->add($card1);
        $box->add($card2);
        $box->setConsumable(true);

        while (!$box->isEmpty()) {
            $result = $box->draw();
            $this->assertTrue($result === $card1 || $result === $card2);
        }

        $box->clear();
        $this->assertTrue($box->isEmpty());
        $box->add($card1);
        $this->assertFalse($box->isEmpty());
        $box->remove($card1);
        $this->assertTrue($box->isEmpty());
    }

    public function testDraw_RandomPositionIsNull()
    {
        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(null));
        $this->assertNull($box->draw());
    }

    public function testDraw_CardIsNull()
    {
        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition', 'find'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(1));
        $box->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));
        $this->assertNull($box->draw());
    }

    public function testDraw_ReturnsCard()
    {
        $card = new IdCard();
        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition', 'find'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(1));
        $box->expects($this->once())
            ->method('find')
            ->will($this->returnValue($card));
        $this->assertEquals($card, $box->draw());
    }

    public function testDraw_ReturnsCardAndNotConsumable()
    {
        $card = new IdCard();
        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition', 'find', 'remove'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(1));
        $box->expects($this->once())
            ->method('find')
            ->will($this->returnValue($card));
        $box->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($card));
        $box->setConsumable(true);
        $this->assertEquals($card, $box->draw());
    }

    public function testAdd()
    {
        $card1 = new IdCard();
        $card1->setRate(10);
        $card2 = new IdCard();
        $card2->setRate(90);

        $box = new LuckyBox();

        $rp1 = new \ReflectionProperty($box, 'cards');
        $rp1->setAccessible(true);
        $rp2 = new \ReflectionProperty($box, 'totalRate');
        $rp2->setAccessible(true);

        $this->assertEquals(array(), $rp1->getValue($box));
        $this->assertEquals(0, $rp2->getValue($box));

        $box->add($card1);
        $this->assertEquals(array($card1), $rp1->getValue($box));
        $this->assertEquals(10, $rp2->getValue($box));

        $box->add($card2);
        $this->assertEquals(array($card1, $card2), $rp1->getValue($box));
        $this->assertEquals(100, $rp2->getValue($box));
    }

    public function testRemove()
    {
        $card1 = new IdCard();
        $card1->setRate(10);
        $card2 = new IdCard();
        $card2->setRate(90);
        $box = new LuckyBox();
        $box->add($card1);
        $box->add($card2);
        $box->remove($card1);

        $rp = new \ReflectionProperty($box, 'cards');
        $rp->setAccessible(true);
        $this->assertEquals(array($card2), $rp->getValue($box));

        $rp = new \ReflectionProperty($box, 'totalRate');
        $rp->setAccessible(true);
        $this->assertEquals(90, $rp->getValue($box));
    }

    public function testClear()
    {
        $card = new IdCard();
        $card->setRate(10);
        $box = new LuckyBox();
        $box->add($card);

        $rp1 = new \ReflectionProperty($box, 'cards');
        $rp1->setAccessible(true);
        $rp2 = new \ReflectionProperty($box, 'totalRate');
        $rp2->setAccessible(true);

        $this->assertCount(1, $rp1->getValue($box));
        $this->assertEquals(10, $rp2->getValue($box));

        $box->clear();

        $this->assertCount(0, $rp1->getValue($box));
        $this->assertEquals(0, $rp2->getValue($box));
    }

    public function testIsEmpty()
    {
        $box = new LuckyBox();
        $this->assertTrue($box->isEmpty());
        $box->add(new IdCard());
        $this->assertFalse($box->isEmpty());
    }

    public function testSetConsumable()
    {
        $box = new LuckyBox();
        $rp = new \ReflectionProperty($box, 'consumable');
        $rp->setAccessible(true);
        $box->setConsumable(false);
        $this->assertFalse($rp->getValue($box));
        $box->setConsumable(true);
        $this->assertTrue($rp->getValue($box));
    }

    public function testIsConsumable()
    {
        $box = new LuckyBox();
        $rp = new \ReflectionProperty($box, 'consumable');
        $rp->setAccessible(true);
        $rp->setValue($box, true);
        $this->assertTrue($box->isConsumable());
        $rp->setValue($box, false);
        $this->assertFalse($box->isConsumable());
    }

    public function testFind_CardIsFound()
    {
        $card = new IdCard();
        $card->setRate(1);
        $box = new LuckyBox();
        $box->add($card);
        $rm = new \ReflectionMethod($box, 'find');
        $rm->setAccessible(true);
        $this->assertEquals($card, $rm->invoke($box, 0));
    }

    public function testFind_CardIsFoundWithMultipleCards()
    {
        $card1 = new IdCard();
        $card1->setRate(1);
        $card2 = new IdCard();
        $card2->setRate(1);
        $box = new LuckyBox();
        $box->add($card1);
        $box->add($card2);
        $rm = new \ReflectionMethod($box, 'find');
        $rm->setAccessible(true);
        $this->assertEquals($card1, $rm->invoke($box, 0));
        $this->assertEquals($card2, $rm->invoke($box, 1));
        $this->assertNull($rm->invoke($box, 2));
    }

    public function testFind_CardIsNotFound()
    {
        $card = new IdCard();
        $card->setRate(0);
        $box = new LuckyBox();
        $box->add($card);
        $rm = new \ReflectionMethod($box, 'find');
        $rm->setAccessible(true);
        $this->assertNull($rm->invoke($box, 0));
    }

    public function testGetRandomPosition_TotalRateIsLessThan1()
    {
        $box = new LuckyBox();
        $rp = new \ReflectionProperty($box, 'totalRate');
        $rp->setAccessible(true);
        $rp->setValue($box, 0);
        $rm = new \ReflectionMethod($box, 'getRandomPosition');
        $rm->setAccessible(true);
        $this->assertNull($rm->invoke($box));
    }

    public function testGetRandomPosition_ReturnsRandomValueBetween0AndTotalRate()
    {
        $box = new LuckyBox();
        $rp = new \ReflectionProperty($box, 'totalRate');
        $rp->setAccessible(true);
        $rp->setValue($box, 1);
        $rm = new \ReflectionMethod($box, 'getRandomPosition');
        $rm->setAccessible(true);
        $this->assertEquals(0, $rm->invoke($box));
    }

}
