<?php
declare(strict_types=1);

namespace App\Collections;

use App\Models\ETFHolding;

class ETFHoldingCollection
{
    private array $etfHoldings;

    public function __construct(array $etfHoldings = [])
    {
        foreach ($etfHoldings as $etfHolding) {
            if (!$etfHolding instanceof ETFHolding) {
                continue;
            }
            $this->add($etfHolding);
        }
    }

    public function add(ETFHolding $etfHolding): void
    {
        $this->etfHoldings[] = $etfHolding;
    }

    public function getAllETFHoldings(): array
    {
        return $this->etfHoldings;
    }
}