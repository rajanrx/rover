<?php

namespace Rover\Unit;

use PHPUnit\Framework\TestCase;
use Rover\Models\Location;
use Rover\RoverManager;

class RoverManagerTest extends TestCase
{
    /** @var  RoverManager */
    protected $instance;

    protected function setUp()
    {
        parent::setUp();
        $this->instance = RoverManager::getInstance();
    }

    public function testCanGetInstance()
    {
        $this->assertInstanceOf(RoverManager::class, $this->instance);
    }

    public function testCanProcessInstructionFromTextFile()
    {
        $outputFile = __DIR__ . '/../data/output.txt';
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        $this->instance->readInputFromFile(__DIR__ . '/../data/input.txt');
        $this->assertNotNull($this->instance->upperRightCoordinates);
        $this->instance->moveRovers();
        $this->instance->writeOutPutToFile($outputFile);
        $this->assertStringEqualsFile($outputFile, "1 3 N\n5 1 E\n");
        $this->assertInstanceOf(
            Location::class,
            $this->instance->upperRightCoordinates
        );
        $this->assertEquals($this->instance->upperRightCoordinates->x, 5);
        $this->assertEquals($this->instance->upperRightCoordinates->y, 5);
        $this->assertNull($this->instance->upperRightCoordinates->orientation);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage  File not found
     */
    public function testWillThrowExceptionIfFileDoesNotExists()
    {
        $this->instance->readInputFromFile(
            __DIR__ . '/../data/nonExistingFile.txt'
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage  Invalid Orientation
     */
    public function testWillThrowExceptionIfOrientationIsInvalid()
    {
        $this->instance->readInputFromFile(
            __DIR__ . '/../data/invalid-data-with-no-orientation.txt'
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid move sequence
     */
    public function testWillThrowExceptionIfInstructionIsInvalid()
    {
        $this->instance->readInputFromFile(
            __DIR__ . '/../data/invalid-data-with-invalid-instructions.txt'
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Initial position provided but no moving
     *     instructions
     */
    public function testWillThrowExceptionIfInstructionMissing()
    {
        $this->instance->readInputFromFile(
            __DIR__ . '/../data/inconsistent-data.txt'
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Initial location not set yet
     */
    public function testWillThrowExceptionIfInitialPositionIsNotSet()
    {
        $this->instance->upperRightCoordinates = null;
        $this->instance->moveRovers();
    }
}
