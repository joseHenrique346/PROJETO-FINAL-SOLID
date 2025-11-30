<?php

namespace App\Application;

use App\Domain\Entity\ParkingSession;
use App\Domain\Pricing\PricingStrategyRegistry;
use App\Domain\Repository\ParkingSessionRepositoryInterface;
use App\Domain\VehicleType;

final class ParkingService
{
    public function __construct(
        private readonly ParkingSessionRepositoryInterface $repository,
        private readonly PricingStrategyRegistry $pricingRegistry
    ) {
    }

    public function registerEntry(string $plate, VehicleType $type, ?\DateTimeImmutable $entryAt = null): ParkingSession
    {
        $entryAt = $entryAt ?? new \DateTimeImmutable('now');
        $session = new ParkingSession(
            id: bin2hex(random_bytes(8)),
            plate: strtoupper($plate),
            vehicleType: $type,
            entryAt: $entryAt
        );
        $this->repository->start($session);
        return $session;
    }

    public function registerExit(string $plate, ?\DateTimeImmutable $exitAt = null): ParkingSession
    {
        $active = $this->repository->findActiveByPlate(strtoupper($plate));
        if (!$active) {
            throw new \RuntimeException('Veículo não encontrado em sessão ativa');
        }

        $exitAt = $exitAt ?? new \DateTimeImmutable('now');
        $hours = $this->diffHoursCeil($active->entryAt, $exitAt);
        $strategy = $this->pricingRegistry->get($active->vehicleType);
        $amount = $strategy->calculate($hours);

        $active->exitAt = $exitAt;
        $active->totalHours = $hours;
        $active->amount = $amount;

        $this->repository->finish($active);
        return $active;
    }

    public function report(): array
    {
        return $this->repository->reportTotals();
    }

    private function diffHoursCeil(\DateTimeImmutable $start, \DateTimeImmutable $end): int
    {
        $seconds = $end->getTimestamp() - $start->getTimestamp();
        $hours = $seconds / 3600;
        return (int) ceil($hours);
    }
}

?>