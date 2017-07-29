<?php

namespace Rover\Models\Interfaces;

use Rover\Models\Location;

interface MovableInterface
{
    /**
     * Sets the current location of the Rover
     * @param Location $location
     */
    public function setLocation(Location $location);

    /**
     * Returns rover location
     * @return Location
     */
    public function getLocation(): Location;

    /**
     * Turns Rover to right and returns new location
     * @return Location
     */
    public function turnRight(): Location;

    /**
     * Turns Rover to left and returns new location
     * @return Location
     */
    public function turnLeft(): Location;

    /**
     * Moves one grid forward and returns new location
     * @return Location
     */
    public function move(): Location;
}
