<?php

namespace Rover\Unit\Models;

use PHPUnit\Framework\TestCase;
use Rover\Models\Location;

class LocationTest extends TestCase
{
    public function testCanGetLocationFromString()
    {
        $string = '1 2';
        $locationWithoutOrientation =
            Location::getLocationFromString($string, false);
        $this->assertEquals(1, $locationWithoutOrientation->x);
        $this->assertEquals(2, $locationWithoutOrientation->y);
        $this->assertNull($locationWithoutOrientation->orientation);

        $string2 = '1 2 N';
        $locationWithOrientation = Location::getLocationFromString($string2);
        $this->assertEquals(1, $locationWithOrientation->x);
        $this->assertEquals(2, $locationWithOrientation->y);
        $this->assertEquals('N', $locationWithOrientation->orientation);

        return [$string2, $locationWithOrientation];
    }

    /**
     * @depends testCanGetLocationFromString
     * @param $params
     */
    public function testLocationCanBeRetrievedAsString($params)
    {
        /** @var Location $location */
        list($string, $location) = $params;
        $this->assertEquals($string, $location->toString());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage X,Y coordinates missing on line R L N
     */
    public function testWillThrowExceptionIfInvalidXYCorditeIsProvided()
    {
        $string = 'R L N';
        Location::getLocationFromString($string);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid Orientation
     */
    public function testWillThrowExceptionIfOrientationIsNotProvided()
    {
        $string = '2 1 ';
        Location::getLocationFromString($string);
    }
}
