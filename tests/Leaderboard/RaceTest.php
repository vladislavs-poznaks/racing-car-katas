<?php

declare(strict_types=1);

namespace Tests\Leaderboard;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use RacingCar\Leaderboard\Driver;
use RacingCar\Leaderboard\Race;
use RacingCar\Leaderboard\SelfDrivingCar;

class RaceTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        parent::setUp();
    }

    private function getDriver(): Driver
    {
        return new Driver($this->faker->name, $this->faker->countryCode());
    }

    private function getSelfDrivingCarDriver(): SelfDrivingCar
    {
        return new SelfDrivingCar($this->faker->semver(), $this->faker->company());
    }

    /** @test */
    public function it_calculates_driver_points_with_real_drivers(): void
    {
        $race = new Race($this->faker->country() . ' - Grand Prix', [
            $driverOne = $this->getDriver(),
            $driverTwo = $this->getDriver(),
            $driverThree = $this->getDriver(),
        ]);

        $this->assertSame(25, $race->getPoints($driverOne));
        $this->assertSame(18, $race->getPoints($driverTwo));
        $this->assertSame(15, $race->getPoints($driverThree));
    }

    /** @test */
    public function it_calculates_driver_points_with_self_driving_cars(): void
    {
        $race = new Race($this->faker->country() . ' - Grand Prix', [
            $driverOne = $this->getSelfDrivingCarDriver(),
            $driverTwo = $this->getSelfDrivingCarDriver(),
            $driverThree = $this->getSelfDrivingCarDriver(),
        ]);

        $this->assertSame(25, $race->getPoints($driverOne));
        $this->assertSame(18, $race->getPoints($driverTwo));
        $this->assertSame(15, $race->getPoints($driverThree));
    }

    /** @test */
    public function it_calculates_driver_points_with_mixed_cars(): void
    {
        $race = new Race($this->faker->country() . ' - Grand Prix', [
            $driverOne = $this->getDriver(),
            $driverTwo = $this->getDriver(),
            $driverThree = $this->getSelfDrivingCarDriver(),
        ]);

        $this->assertSame(25, $race->getPoints($driverOne));
        $this->assertSame(18, $race->getPoints($driverTwo));
        $this->assertSame(15, $race->getPoints($driverThree));
    }
}
