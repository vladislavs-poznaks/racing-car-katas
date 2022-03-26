<?php

declare(strict_types=1);

namespace Tests\Leaderboard;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use RacingCar\Leaderboard\Driver;
use RacingCar\Leaderboard\Leaderboard;
use RacingCar\Leaderboard\Race;
use RacingCar\Leaderboard\SelfDrivingCar;

class LeaderboardTest extends TestCase
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
    public function it_determines_driver_points_correctly(): void
    {
        $driverOne = $this->getDriver();
        $driverTwo = $this->getDriver();
        $driverThree = $this->getSelfDrivingCarDriver();

        $raceOne = new Race($this->faker->country() . ' - Grand Prix', [$driverTwo, $driverOne, $driverThree]);
        $raceTwo = new Race($this->faker->country() . ' - Grand Prix', [$driverTwo, $driverOne, $driverThree]);
        $raceThree = new Race($this->faker->country() . ' - Grand Prix', [$driverOne, $driverTwo, $driverThree]);

        $leaderboard = new Leaderboard([$raceOne, $raceTwo, $raceThree]);

        $results = $leaderboard->getDriverResults();

        $this->assertArrayHasKey($driverOne->name, $results);
        $this->assertSame(18 + 18 + 25, $results[$driverOne->name]);
    }

    /** @test */
    public function it_should_find_a_winner(): void
    {
        $driverOne = $this->getDriver();
        $driverTwo = $this->getDriver();
        $driverThree = $this->getSelfDrivingCarDriver();

        $raceOne = new Race($this->faker->country() . ' - Grand Prix', [$driverOne, $driverTwo, $driverThree]);
        $raceTwo = new Race($this->faker->country() . ' - Grand Prix', [$driverTwo, $driverOne, $driverThree]);
        $raceThree = new Race($this->faker->country() . ' - Grand Prix', [$driverOne, $driverTwo, $driverThree]);

        $leaderboard = new Leaderboard([$raceOne, $raceTwo, $raceThree]);

        $result = $leaderboard->getDriverRankings();

        $this->assertSame($driverOne->name, $result[0]);
    }

    /** @test */
    public function it_should_keep_all_the_drivers_when_same_points(): void
    {
        // setup
        $driverOne = $this->getDriver();
        $driverTwo = $this->getDriver();
        $driverThree = $this->getDriver();

        // bug, drops drivers with same points
        $raceOne = new Race($this->faker->country() . ' - Grand Prix', [$driverOne, $driverTwo, $driverThree]);
        $raceTwo = new Race($this->faker->country() . ' - Grand Prix', [$driverTwo, $driverOne, $driverThree]);

        $leaderboard = new Leaderboard([$raceOne, $raceTwo]);

        // act
        $rankings = $leaderboard->getDriverRankings();

        // verify
        $this->assertSame([$driverOne->name, $driverTwo->name, $driverThree->name], $rankings);
        // note: the order of driver1 and driver2 is platform dependent
    }
}
