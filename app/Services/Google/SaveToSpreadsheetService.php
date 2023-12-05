<?php

declare(strict_types=1);

namespace App\Services\Google;

use App\Repositories\ETFScraperRepository;
use App\Repositories\GoogleSpreadsheetRepository;
use Google\Client;

class SaveToSpreadsheetService
{
    public function execute(
        string $url,
        string $spreadsheetID,
        string $range
    ): void
    {
        $client = new Client();
        $ethData = new ETFScraperRepository(new GoogleSpreadsheetRepository($client));
        $ethData->scrapeETFData($url, $spreadsheetID, $range);
    }
}