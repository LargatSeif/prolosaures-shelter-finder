<?php

namespace Tests\Unit\Services;

use App\Services\ShelterCalculator;
use PHPUnit\Framework\TestCase;

class ShelterCalculatorTest extends TestCase
{
    private ShelterCalculator $shelterCalculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shelterCalculator = new ShelterCalculator();
    }

    public function testEmptyAltitudesReturnsZeroShelteredArea()
    {
        $result = $this->shelterCalculator->calculateShelteredArea([]);

        $this->assertEquals(0, $result['shelteredArea']);
        $this->assertEquals([], $result['altitudes']);
        $this->assertEquals([], $result['sheltered']);
    }

    public function testSingleAltitudeReturnsZeroShelteredArea()
    {
        $result = $this->shelterCalculator->calculateShelteredArea([5]);

        $this->assertEquals(0, $result['shelteredArea']);
        $this->assertEquals([5], $result['altitudes']);
        $this->assertEquals([false], $result['sheltered']);
    }

    public function testAscendingAltitudesReturnsZeroShelteredArea()
    {
        $altitudes = [1, 2, 3, 4, 5];
        $result = $this->shelterCalculator->calculateShelteredArea($altitudes);

        $this->assertEquals(0, $result['shelteredArea']);
        $this->assertEquals($altitudes, $result['altitudes']);
        $this->assertEquals([false, false, false, false, false], $result['sheltered']);
    }

    public function testDescendingAltitudesReturnsZeroShelteredArea()
    {
        $altitudes = [5, 4, 3, 2, 1];
        $result = $this->shelterCalculator->calculateShelteredArea($altitudes);

        $this->assertEquals(4, $result['shelteredArea']);
        $this->assertEquals($altitudes, $result['altitudes']);
        $this->assertEquals([false, true, true, true, true], $result['sheltered']);
    }

    public function testMixedAltitudesCalculatesCorrectShelteredArea()
    {
        $altitudes = [3, 1, 4, 2, 5];
        $result = $this->shelterCalculator->calculateShelteredArea($altitudes);

        $this->assertEquals(2, $result['shelteredArea']);
        $this->assertEquals($altitudes, $result['altitudes']);
        $this->assertEquals([false, true, false, true, false], $result['sheltered']);
    }

    public function testValleysCalculatesCorrectShelteredArea()
    {
        $altitudes = [5, 2, 1, 3, 6];
        $result = $this->shelterCalculator->calculateShelteredArea($altitudes);

        $this->assertEquals(3, $result['shelteredArea']);
        $this->assertEquals($altitudes, $result['altitudes']);
        $this->assertEquals([false, true, true, true, false], $result['sheltered']);
    }

    public function testEqualAltitudesCalculatesCorrectShelteredArea()
    {
        $altitudes = [3, 3, 3, 3, 3];
        $result = $this->shelterCalculator->calculateShelteredArea($altitudes);

        $this->assertEquals(0, $result['shelteredArea']);
        $this->assertEquals($altitudes, $result['altitudes']);
        $this->assertEquals([false, false, false, false, false], $result['sheltered']);
    }
}