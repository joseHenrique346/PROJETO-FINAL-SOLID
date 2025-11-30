<?php

namespace App\Domain\Pricing;

interface PricingStrategyInterface//
{
    public function calculate(int $hours): float;
}
