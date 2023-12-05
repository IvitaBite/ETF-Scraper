<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\ETFHoldingCollection;

class ETF
{
    private string $etfSymbol;
    private ETFHoldingCollection $holdingCollection;

    public function __construct(
        string               $etfSymbol,
        ETFHoldingCollection $holdingCollection
    )
    {
        $this->etfSymbol = $etfSymbol;
        $this->holdingCollection = $holdingCollection;
    }

    public function getETFSymbol(): string
    {
        return $this->etfSymbol;
    }

    public function getHoldingCollection(): ETFHoldingCollection
    {
        return $this->holdingCollection;
    }
}