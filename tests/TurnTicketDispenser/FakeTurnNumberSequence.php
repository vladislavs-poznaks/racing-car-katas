<?php

namespace Tests\TurnTicketDispenser;

use RacingCar\TurnTicketDispenser\TurnNumberSequenceInterface;

class FakeTurnNumberSequence implements TurnNumberSequenceInterface
{
    private static $turnNumber = 0;

    public static function nextTurn(): int
    {
        return self::$turnNumber++;
    }

    public function resetTurns(): void
    {
        self::$turnNumber = 0;
    }
}