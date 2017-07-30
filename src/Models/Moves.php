<?php

namespace Rover\Models;

class Moves
{
    const TURN_RIGHT = 'R';
    const TURN_LEFT = 'L';
    const MOVE = 'M';

    /** @var  Rover Rover object that need to move */
    protected $rover;

    /** @var  string  Sequence of instructions provided */
    protected $instructions;

    /** @var Location  Higher location boundary for rover to move */
    protected $upperLimit;

    /** @var Location Lower location boundary for rover to move */
    protected $lowerLimit;

    /**
     * Moves constructor.
     * @param Rover    $rover
     * @param string   $moves
     * @param Location $upperLimit
     * @param Location $lowerLimit
     */
    public function __construct(
        Rover $rover,
        string $moves,
        Location $upperLimit,
        Location $lowerLimit
    ) {
        $this->rover = $rover;
        $this->instructions = $moves;
        $this->upperLimit = $upperLimit;
        $this->lowerLimit = $lowerLimit;
    }

    /**
     * Returns array of valid moves
     * @return array
     * @codeCoverageIgnore
     */
    public static function validMoves(): array
    {
        return [
            self::TURN_RIGHT,
            self::TURN_LEFT,
            self::MOVE,
        ];
    }

    /**
     * Processes the Rover moves based on provided instructions and returns last
     * location
     * @return Location
     * @throws \Exception
     */
    public function processMoves(): Location
    {
        $moves = str_split($this->instructions);
        foreach ($moves as $move) {
            switch ($move) {
                case self::TURN_RIGHT:
                    $this->rover->turnRight();
                    break;
                case self::TURN_LEFT:
                    $this->rover->turnLeft();
                    break;
                case self::MOVE:
                    $this->rover->move();
                    break;
                default:
                    throw new \Exception('Invalid move command');
            }
            $this->checkOutOfBounds();
        }

        return $this->rover->getLocation();
    }

    /**
     * @return Rover
     * @codeCoverageIgnore
     */
    public function getRover(): Rover
    {
        return $this->rover;
    }

    /**
     * Check if Rover is trying to cross defined boundaries
     * @throws \Exception
     */
    public function checkOutOfBounds()
    {
        $location = $this->rover->getLocation();
        if ($location->x < $this->lowerLimit->x ||
            $location->y < $this->lowerLimit->y ||
            $location->x > $this->upperLimit->x ||
            $location->y > $this->upperLimit->y) {
            throw new \Exception(
                'Location out of bounds ' . $location->toString()
            );
        }
    }
}
