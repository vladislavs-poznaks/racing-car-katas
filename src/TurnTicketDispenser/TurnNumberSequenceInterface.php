<?php

namespace RacingCar\TurnTicketDispenser;

interface TurnNumberSequenceInterface
{
    public static function nextTurn(): int;
}