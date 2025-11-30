<?php

namespace App\Domain\Pricing;

use App\Domain\VehicleType;

final class PricingStrategyRegistry//
{
    /** @var array<string, PricingStrategyInterface> */
    private array $strategies = [];

    public function register(VehicleType $type, PricingStrategyInterface $strategy): void
    {
        $this->strategies[$type->value] = $strategy;//
    }

    public function get(VehicleType $type): PricingStrategyInterface
    {
        return $this->strategies[$type->value];
    }
}

?>