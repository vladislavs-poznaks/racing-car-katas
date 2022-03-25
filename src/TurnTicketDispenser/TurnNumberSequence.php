<?php

declare(strict_types=1);

namespace RacingCar\TurnTicketDispenser;

class TurnNumberSequence implements TurnNumberSequenceInterface
{
    private static $turnNumber = 0;

    public static function nextTurn(): int
    {
        return self::$turnNumber++;
    }
}
