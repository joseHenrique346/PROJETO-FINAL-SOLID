<?php

namespace App\Domain\Entity;

use App\Domain\VehicleType;

final class ParkingSession//
{
    public function __construct(
        public readonly string $id,
        public readonly string $plate,
        public readonly VehicleType $vehicleType,
        public readonly \DateTimeImmutable $entryAt,
        public ?\DateTimeImmutable $exitAt = null,
        public ?int $totalHours = null,
        public ?float $amount = null
    ) {
    }
}