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

    /** @var  Location */
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
     * @param Moves $moves
     */
    protected function enqueue(Moves $moves)
    {
        $this->moves[] = $moves;
    }

    /**
     * @throws \Exception
     */
    public function moveRovers()
    {
        if (!self::$upperRightCoordinates instanceof Location) {
            throw new \Exception('Initial location not set yet');
        }
        foreach ($this->moves as $move) {
            $move->processMoves();
        }
    }

    /**
     * @param string $filename
     * @throws \Exception
     */
    public function readInputFromFile(string $filename)
    {
        // Reset upper right co-ordinates
        self::$upperRightCoordinates = null;
        $position = null;
        $file = @fopen($filename, "r");
        if (!$file) {
            throw new \Exception('File not found');
        }
        while (!feof($file)) {
            $line = trim(fgets($file));
            // TODO: support comments on text file
            if (strlen($line) == 0) {
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
                    $this->getInstructions($line),
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

    /**
     * @param string $filename
     */
    public function writeOutPutToFile(string $filename)
    {
        $output = '';
        foreach ($this->moves as $move) {
            $output .=
                "{$move->getRover()->getLocation()->toString()}" . PHP_EOL;
        }
        file_put_contents($filename, $output);
    }

    /**
     * @param string $line
     * @return string
     * @throws \Exception
     */
    private function getInstructions(string $line)
    {
        $line = trim($line);
        $lmrString =
            Moves::TURN_LEFT . Moves::TURN_RIGHT . Moves::MOVE;
        if (preg_match("/[{^$lmrString}]+$/", $line) === 0) {
            throw new \Exception("Invalid move sequence: {$line}");
        }

        return $line;
    }
}
