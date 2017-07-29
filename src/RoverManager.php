<?php

namespace Rover;

use Rover\Models\Location;
use Rover\Models\Moves;
use Rover\Models\Rover;

class RoverManager
{
    /** @var  RoverManager */
    protected static $instance;

    /** @var  Moves[] */
    protected $moves;

    public static $upperRightCoordinates;

    /**
     * @return RoverManager
     */
    public static function getInstance(): RoverManager
    {
        if (static::$instance === null) {
            $self = new static();
            static::$instance = $self;
        }

        return static::$instance;
    }

    /**
     * is not allowed to call from outside: private!
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * prevent the instance from being cloned
     *
     * @codeCoverageIgnore
     * @return void
     */
    private function __clone()
    {
    }

    protected function enqueue(Moves $moves)
    {
        $this->moves[] = $moves;
    }

    public function moveRovers()
    {
        if (!self::$upperRightCoordinates instanceof Location) {
            throw new \Exception('Initial location not set yet');
        }
        foreach ($this->moves as $move) {
            $move->processMoves();
        }
    }

    public function readInputFromFile(string $filename)
    {
        $position = null;
        if ($file = fopen($filename, "r")) {
            while (!feof($file)) {
                $line = fgets($file);
                // TODO: check for comments
                if (empty($line)) {
                    continue;
                }
                // First line should be the upper right coordinates of plateau
                if (self::$upperRightCoordinates == null) {
                    self::$upperRightCoordinates =
                        Location::getLocationFromString($line, false);
                    continue;
                }

                // We will get the position first and followed by move instructions
                // So as long as we have null value set for $position , the input
                // must be Rover position
                if ($position == null) {
                    $position = Location::getLocationFromString($line);
                    continue;
                }

                // If we have $startLocation being set as well as $position , then
                // the following string will be the moves instructions.
                // We will store position and moves in `moves` array of `RoverManager`
                // and reset $position to null to grab position for next Rover

                $rover = new Rover();
                $rover->setLocation($position);
                $this->enqueue(
                    new Moves(
                        $rover,
                        $this->getMoves($line),
                        self::$upperRightCoordinates,
                        new Location(0, 0, null)
                    )
                );
                $position = null;
            }

            // If position is not null at this point then we have a situation
            // where we have position but no moves.
            if ($position != null) {
                throw new \Exception(
                    'Initial position provided but no moving instructions'
                );
            }
            fclose($file);
        }
    }

    public function writeOutPutToFile(string $filename)
    {
        foreach ($this->moves as $move) {
            echo "{$move->getRover()->getLocation()->toString()} \n";
        }
    }

    private function getMoves(string $line)
    {
        // TODO: Validate
        return trim($line);
    }
}