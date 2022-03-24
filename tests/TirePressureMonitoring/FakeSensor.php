<?php

namespace Tests\TirePressureMonitoring;

use RacingCar\TirePressureMonitoring\SensorInterface;

class FakeSensor implements SensorInterface
{
    public function __construct(private int $psiValue)
    {
    }

    public function popNextPressurePsiValue(): int
    {
        return $this->psiValue;
    }
}