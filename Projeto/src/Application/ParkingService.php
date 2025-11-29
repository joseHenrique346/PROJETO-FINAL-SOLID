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

    public function registerEntry(): ParkingSession
    {
        
    }

    public function registerExit(): ParkingSession
    {
        
    }

    public function report(): array
    {

    }

    private function diffHoursCeil(): int
    {
       
    }
}
