<?php

namespace RacingCar\TelemetrySystem;

interface TelemetryClientInterface
{
    public function connect(string $telemetryServerConnectionString): void;

    public function disconnect(): void;

    public function send(string $message): void;

    public function receive(): string;

    public function getOnlineStatus(): bool;
}