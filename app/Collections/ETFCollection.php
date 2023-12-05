<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\ETF;

class ETFCollection
{
    private array $etfCollection;

    public function __construct(array $etfCollection = [])
    {
        foreach ($etfCollection as $etf) {
            if (!$etf instanceof ETF) {
                continue;
            }
            $this->add($etf);
        }
    }

    public function add(ETF $etf): void
    {
        $this->etfCollection[] = $etf;
    }

    public function getAllETF(): array
    {
        return $this->etfCollection;
    }
}