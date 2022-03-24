<?php

declare(strict_types=1);

namespace Tests\TirePressureMonitoring;

use PHPUnit\Framework\TestCase;
use RacingCar\TirePressureMonitoring\Alarm;
use RacingCar\TirePressureMonitoring\SensorInterface;

class AlarmTest extends TestCase
{
    /**
     * @test
     * @dataProvider pressures
     */
    public function it_checks_for_correct_alarm_state_for_given_pressures(SensorInterface $sensor, bool $expected)
    {
        $alarm = new Alarm($sensor);

        $alarm->check();

        $this->assertEquals($expected, $alarm->isAlarmOn());
    }

    function pressures(): array
    {
        return [
            [new FakeSensor(16), true],
            [new FakeSensor(17), false],
            [new FakeSensor(18), false],
            [new FakeSensor(19), false],
            [new FakeSensor(20), false],
            [new FakeSensor(21), false],
            [new FakeSensor(22), true],
        ];
    }
}
