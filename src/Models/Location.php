<?php

namespace Rover\Models;

class Location
{
    const NORTH = 'N';
    const EAST = 'E';
    const SOUTH = 'S';
    const WEST = 'W';

    /** @var int X co-ordinate in 2D space */
    public $x;

    /** @var int Y co-ordinate in 2D space */
    public $y;

    /** @var  string  Geographical direction */
    public $orientation;

    /**
     * Location constructor.
     * @param $x
     * @param $y
     * @param $orientation
     */
    public function __construct($x, $y, $orientation)
    {
        $this->x = (integer)$x;
        $this->y = (integer)$y;
        $this->orientation = $orientation;
    }

    /**
     * Returns available geographical directions
     * @return array
     */
    public static function getClockwiseOrientations(): array
    {
        return [
            self::NORTH,
            self::EAST,
            self::SOUTH,
            self::WEST,
        ];
    }

    /**
     * Returns string format for given Location
     * @return string
     */
    public function toString(): string
    {
        return "{$this->x} {$this->y} {$this->orientation}";
    }

    /**
     * Extracts Location from given string
     * @param string  $line
     * @param boolean $orientationRequired
     * @return Location
     * @throws \Exception
     */
    public static function getLocationFromString(
        string $line,
        $orientationRequired = true
    ) {
        $line = trim($line);
        $ordinates = explode(' ', $line);
        if (!array_key_exists(0, $ordinates) ||
            !array_key_exists(1, $ordinates) ||
            !is_numeric($ordinates[0]) || !is_numeric($ordinates[1])) {
            {
                throw new \Exception("X,Y coordinates missing on line {$line}");
            }
        }
        if ($orientationRequired && (!array_key_exists(2, $ordinates) ||
                !in_array($ordinates[2], self::getClockwiseOrientations()))
        ) {
            throw new \Exception('Invalid Orientation');
        }

        $orientation = $orientationRequired ? $ordinates[2] : null;
        $location =
            new Location($ordinates[0], $ordinates[1], $orientation);

        return $location;
    }
}
