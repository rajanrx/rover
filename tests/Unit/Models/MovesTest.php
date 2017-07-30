<?php

namespace Rover\Unit\Models;

use PHPUnit\Framework\TestCase;
use Rover\Models\Location;
use Rover\Models\Moves;
use Rover\Models\Rover;

class MovesTest extends TestCase
{
    /** @var  Rover */
    protected $rover;

    /** @var  Location */
    protected $lowerLimit;

    /** @var  Location */
    protected $upperLimit;


    protected function setUp()
    {
        parent::setUp();
        $this->rover = new Rover();
        $this->rover->setLocation(new Location(1, 2, Location::NORTH));
        $this->lowerLimit = new Location(0, 0, null);
        $this->upperLimit = new Location(5, 5, null);
    }

    public function testProcessingInstructionsWillHeadToRightLocation()
    {
        $moves = $this->getMoves('LMLMLMLMM');
        $moves->processMoves();
        $roverNewLocation = $this->rover->getLocation();
        $this->assertEquals('1 3 N', $roverNewLocation->toString());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid move command
     */
    public function testWillThrowExceptionIfInValidMoveSequenceIsProvided()
    {
        $moves = $this->getMoves('FOO');
        $moves->processMoves();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Location out of bounds 1 -1 S
     */
    public function testWillThrowOutOfBoundsIfItCrossesLowerLimits()
    {
        $moves = $this->getMoves('RRMMM');
        $moves->processMoves();
    }

    protected function getMoves(string $instructions)
    {
        return new Moves(
            $this->rover,
            $instructions,
            $this->upperLimit,
            $this->lowerLimit
        );
    }
}
