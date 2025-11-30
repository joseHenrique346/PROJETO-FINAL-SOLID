<?php

namespace App\Domain\Repository;

use App\Domain\Entity\ParkingSession;//

interface ParkingSessionRepositoryInterface
{
    public function start(ParkingSession $session): void;
    public function finish(ParkingSession $session): void;
    public function findActiveByPlate(string $plate): ?ParkingSession;
    /**
     * @return array{
     * carro: array{count: int, amount: float, hours: int},
     * moto: array{count: int, amount: float, hours: int},
     * caminhao: array{count: int, amount: float, hours: int}
     * }
     */
    public function reportTotals(): array;
}
