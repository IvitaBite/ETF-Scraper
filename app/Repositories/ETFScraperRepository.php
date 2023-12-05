<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Collections\ETFCollection;
use App\Collections\ETFHoldingCollection;
use App\Models\ETF;
use App\Models\ETFHolding;

class ETFScraperRepository
{
    private GoogleSpreadsheetRepository $sheetsRepository;

    public function __construct(GoogleSpreadsheetRepository $sheetsRepository)
    {
        $this->sheetsRepository = $sheetsRepository;
    }

    public function scrapeETFData(
        string $url,
        string $spreadsheetID,
        string $range
    ): ETFCollection
    {
        $command = "node etf_scraper.js \"$url\"";
        $result = exec($command);
        $decodedETFData = json_decode($result, true);

        $etfCollection = new ETFCollection();

        foreach ($decodedETFData['etfSymbols'] as $etfSymbol) {
            $holdingCollection = new ETFHoldingCollection();

            foreach ($decodedETFData['holdingCollection'][$etfSymbol] as $holdingData) {
                $etfHolding = new ETFHolding(
                    $holdingData['holdingSymbol'],
                    $holdingData['holdingName'],
                    $holdingData['assets']
                );

                $holdingCollection->add($etfHolding);
            }

            $etf = new ETF($etfSymbol, $holdingCollection);
            $etfCollection->add($etf);
        }

        $this->sheetsRepository->saveToSheet($spreadsheetID, $range, $etfCollection);
        return $etfCollection;
    }
}