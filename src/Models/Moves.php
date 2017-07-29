<?php


namespace Rover\Models;


class Moves
{
    const TURN_RIGHT = 'R';
    const TURN_LEFT = 'L';
    const MOVE = 'M';

    /** @var  Rover */
    protected $rover;

    /** @var  string */
    protected $instructions;

    public function __construct(
        $rover,
        $moves,
        Location $upperLimit,
        Location $lowerLimit
    ) {
        $this->rover = $rover;
        $this->instructions = $moves;
        $this->upperLimit = $upperLimit;
        $this->lowerLimit = $lowerLimit;
    }

    protected $upperLimit;
    protected $lowerLimit;

    public static function validMoves()
    {
        return [
            self::TURN_RIGHT,
            self::TURN_LEFT,
            self::MOVE,
        ];
    }

    public function processMoves()
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
                case self::MOVE;
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
     */
    public function getRover(): Rover
    {
        return $this->rover;
    }

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