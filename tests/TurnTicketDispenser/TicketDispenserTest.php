<?php

declare(strict_types=1);

namespace Tests\TurnTicketDispenser;

use PHPUnit\Framework\TestCase;
use RacingCar\TurnTicketDispenser\TicketDispenser;

class TicketDispenserTest extends TestCase
{
    /** @test */
    public function it_dispenses_ticket_with_one_dispenser(): void
    {
        $turnSequence = new FakeTurnNumberSequence();
        $turnSequence->resetTurns();

        $dispenser = new TicketDispenser($turnSequence);
        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(0, $ticket->getTurnNumber());

        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(1, $ticket->getTurnNumber());

        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(2, $ticket->getTurnNumber());
    }

    /** @test */
    public function it_dispenses_ticket_with_multiple_dispensers(): void
    {
        $turnSequence = new FakeTurnNumberSequence();
        $turnSequence->resetTurns();

        $dispenser = new TicketDispenser($turnSequence);
        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(0, $ticket->getTurnNumber());

        $dispenser = new TicketDispenser($turnSequence);
        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(1, $ticket->getTurnNumber());

        $dispenser = new TicketDispenser($turnSequence);
        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(2, $ticket->getTurnNumber());
    }
}
