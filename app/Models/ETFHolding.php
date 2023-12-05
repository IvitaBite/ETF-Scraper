<?php

declare(strict_types=1);

namespace App\Models;

class ETFHolding
{
    private string $holdingSymbol;
    private string $holdingName;
    private string $assets;

    public function __construct(
        string $holdingSymbol,
        string $holdingName,
        string $assets
    )
    {
        $this->holdingSymbol = $holdingSymbol;
        $this->holdingName = $holdingName;
        $this->assets = $assets;
    }

    public function getHoldingSymbol(): string
    {
        return $this->holdingSymbol;
    }

    public function getHoldingName(): string
    {
        return $this->holdingName;
    }

    public function getAssets(): string
    {
        return $this->assets;
    }
}