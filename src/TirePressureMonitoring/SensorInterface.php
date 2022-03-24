<?php

namespace RacingCar\TirePressureMonitoring;

interface SensorInterface
{
    public function popNextPressurePsiValue(): int;
}