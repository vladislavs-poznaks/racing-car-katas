<?php

declare(strict_types=1);

namespace Tests\TelemetrySystem;

use Exception;
use PHPUnit\Framework\TestCase;
use RacingCar\TelemetrySystem\TelemetryDiagnosticControls;

class TelemetryDiagnosticControlsTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_unable_to_connect()
    {
        $this->expectException(Exception::class);

        $client = new FakeTelemetryClient();

        $client
            ->withTriesUntilSuccessfulConnection(4);

        $controls = new TelemetryDiagnosticControls($client);

        $controls->checkTransmission();
    }

    /** @test */
    public function it_disconnects_and_tries_to_connect_at_most_three_times()
    {
        $client = new FakeTelemetryClient();

        $client
            ->withTriesUntilSuccessfulConnection(4);

        $controls = new TelemetryDiagnosticControls($client);

        try {
            $controls->checkTransmission();
        } catch (Exception $e) {
            /** @var FakeTelemetryClient $client */
            $client = $controls->getClient();

            $this->assertEquals(1, $client->callCount('disconnect'));
            $this->assertEquals(3, $client->callCount('connect'));
        }
    }

    /** @test */
    public function it_sends_and_receives_a_message_on_successful_connection()
    {
        $client = new FakeTelemetryClient();

        $client
            ->withTriesUntilSuccessfulConnection(2);

        $controls = new TelemetryDiagnosticControls($client);

        $controls->checkTransmission();

        /** @var FakeTelemetryClient $client */
        $client = $controls->getClient();

        $this->assertEquals(1, $client->callCount('disconnect'));
        $this->assertEquals(2, $client->callCount('connect'));

        $this->assertEquals(1, $client->callCount('send'));
        $this->assertEquals(1, $client->callCount('receive'));

        $this->assertTrue(in_array('AT#UD', $client->getSentData()));
        $this->assertTrue(in_array('TEST_RESPONSE', $client->getReceivedData()));
    }
}
