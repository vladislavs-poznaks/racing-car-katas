<?php

declare(strict_types=1);

namespace RacingCar\TurnTicketDispenser;

class TicketDispenser
{
    private ?TurnNumberSequenceInterface $sequence;

    public function __construct(TurnNumberSequenceInterface $sequence = null)
    {
        $this->sequence = $sequence ?? new TurnNumberSequence;
    }
    public function getTurnTicket()
    {
        $newTurnNumber = $this->sequence::nextTurn();
        return new TurnTicket($newTurnNumber);
    }
}
