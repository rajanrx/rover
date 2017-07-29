<?php

namespace Rover\Models;

use Rover\Models\Interfaces\MovableInterface;

class Rover implements MovableInterface
{
    /** @var  Location */
    protected $location;

    /**
     * Sets the current location of the Rover
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Returns rover location
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * Turns Rover to right and returns new location
     * @return Location
     */
    public function turnRight(): Location
    {
        return $this->turn(+1);
    }

    /**
     * Turns Rover to left and returns new location
     * @return Location
     */
    public function turnLeft(): Location
    {
        return $this->turn(-1);
    }

    public function turn($value)
    {
        $orientations = Location::getClockwiseOrientations();
        $currentPos = array_search($this->location->orientation, $orientations);
        $newPos = $currentPos + $value;
        if ($newPos > (sizeof($orientations) - 1)) {
            $newPos = 0;
        } elseif ($newPos < 0) {
            $newPos = sizeof($orientations) - 1;
        }
        $this->location->orientation = $orientations[$newPos];

        return $this->location;
    }

    /**
     * Moves one grid forward and returns new location
     * @return Location
     * @throws \Exception
     */
    public function move(): Location
    {
        switch ($this->location->orientation) {
            case Location::NORTH:
                $this->location->y += 1;
                break;
            case Location::EAST:
                $this->location->x += 1;
                break;
            case Location::SOUTH:
                $this->location->y -= 1;
                break;
            case Location::WEST:
                $this->location->x -= 1;
                break;
            default:
                throw new \Exception(
                    "Invalid orientation {$this->location->orientation}"
                );
        }
        return $this->location;
    }
}
