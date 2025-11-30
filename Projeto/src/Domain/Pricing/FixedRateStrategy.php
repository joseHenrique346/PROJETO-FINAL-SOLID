<?php

namespace App\Domain\Pricing;

final class FixedRateStrategy implements PricingStrategyInterface//
{ 
    public function __construct(private readonly float $pricePerHour)
    {
    }

    public function calculate(int $hours): float//
    {
        return $hours * $this->pricePerHour;
    }
}

?>