<?php

namespace Tests\TelemetrySystem;

use RacingCar\TelemetrySystem\TelemetryClientInterface;

class FakeTelemetryClient implements TelemetryClientInterface
{
    private bool $onlineStatus = false;

    private array $calls = [
        'disconnect' => 0,
        'connect' => 0,
        'send' => 0,
        'receive' => 0,
    ];

    private array $sentToClient = [];
    private array $receivedFromClient = [];

    private int $triesUntilSuccessfulConnection = 0;

    public function withTriesUntilSuccessfulConnection(int $tries): self
    {
        $this->triesUntilSuccessfulConnection = $tries - 1;

        return $this;
    }

    public function callCount(string $method): ?int
    {
        return $this->calls[$method] ?? null;
    }

    public function getSentData(): array
    {
        return $this->sentToClient;
    }

    public function getReceivedData(): array
    {
        return $this->receivedFromClient;
    }

    public function disconnect(): void
    {
        $this->onlineStatus = false;

        $this->calls['disconnect']++;
    }

    public function connect(string $telemetryServerConnectionString): void
    {
        if ($this->triesUntilSuccessfulConnection === 0) {
            $this->onlineStatus = true;
        } else {
            $this->triesUntilSuccessfulConnection--;
        }

        $this->calls['connect']++;
    }

    public function send(string $message): void
    {
        $this->sentToClient[] = $message;

        $this->calls['send']++;
    }

    public function receive(): string
    {
        $this->calls['receive']++;

        $this->receivedFromClient[] = 'TEST_RESPONSE';

        return 'TEST_RESPONSE';
    }

    public function getOnlineStatus(): bool
    {
        return $this->onlineStatus;
    }
}