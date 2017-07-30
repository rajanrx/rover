<?php

namespace Rover\Unit\Models;

use PHPUnit\Framework\TestCase;
use Rover\Models\Location;
use Rover\Models\Rover;

/**
 * @property Rover rover
 */
class RoverTest extends TestCase
{
    /** @var  Rover */
    protected $rover;

    public function setUp()
    {
        parent::setUp();
        $this->rover = new Rover();
        $this->rover->setLocation(new Location(1, 1, Location::NORTH));
    }

    public function testCanSetLocation()
    {
        $this->assertCurrentLocation('1 1 N');
    }

    public function testTurnRight()
    {
        $this->rover->turnRight();
        $this->assertCurrentLocation('1 1 E');
        $this->rover->turnRight();
        $this->assertCurrentLocation('1 1 S');
        $this->rover->turnRight();
        $this->assertCurrentLocation('1 1 W');
        $this->rover->turnRight();
        $this->assertCurrentLocation('1 1 N');
        $this->rover->turnRight();
        $this->assertCurrentLocation('1 1 E');
    }

    public function testTurnLeft()
    {
        $this->rover->turnLeft();
        $this->assertCurrentLocation('1 1 W');
        $this->rover->turnLeft();
        $this->assertCurrentLocation('1 1 S');
        $this->rover->turnLeft();
        $this->assertCurrentLocation('1 1 E');
        $this->rover->turnLeft();
        $this->assertCurrentLocation('1 1 N');
        $this->rover->turnLeft();
        $this->assertCurrentLocation('1 1 W');
    }

    public function testCanMove()
    {
        $this->rover->move();
        $this->assertCurrentLocation('1 2 N');
        $this->rover->turnLeft();
        $this->rover->move();
        $this->assertCurrentLocation('0 2 W');
        $this->rover->turnRight();
        $this->rover->move();
        $this->assertCurrentLocation('0 3 N');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid orientation X
     */
    public function testWillThrowExceptionIfInvalidOrientationIsProvided()
    {
        $this->rover->setLocation(new Location(0, 0, 'X'));
        $this->rover->move();
    }

    protected function assertCurrentLocation($locationString)
    {
        $this->assertEquals(
            $locationString,
            $this->rover->getLocation()->toString()
        );
    }
}
