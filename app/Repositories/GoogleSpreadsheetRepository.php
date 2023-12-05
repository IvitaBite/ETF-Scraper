<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Collections\ETFCollection;
use App\Collections\ETFHoldingCollection;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSpreadsheetRepository
{
    private Client $client;
    private Sheets $sheets;

    public function __construct()
    {
        $this->client = new Client();
        $serviceAccountKeyPath = $_ENV['SERVICE_ACCOUNT_KEY_PATH'] ?? null;
        if ($serviceAccountKeyPath !== null) {
            $this->client->setAuthConfig(__DIR__ . "/$serviceAccountKeyPath");
            $this->client->addScope(Sheets::SPREADSHEETS);
            $this->sheets = new Sheets($this->client);
        } else {
            throw new \Exception('SERVICE_ACCOUNT_KEY_PATH environment variable not set.');
        }
    }

    public function saveToSheet(
        string        $spreadsheetID,
        string        $range,
        ETFCollection $etfCollection
    ): void
    {
        $values = $this->prepareDataForSaving($etfCollection);
        $updateBody = new ValueRange([
            'values' => $values
        ]);
        $updateOptions = ['valueInputOption' => 'RAW'];
        $this->sheets->spreadsheets_values->update($spreadsheetID, $range, $updateBody, $updateOptions);
    }

    private function prepareDataForSaving(ETFCollection $etfCollection): array
    {
        $values = [['ETF Symbols', 'Holding Symbols', 'Holding Name', 'Holding Percentage']];

        foreach ($etfCollection->getAllETF() as $etf) {
            /** @var ETFCollection $etfSymbol */
            $etfSymbol = $etf->getETFSymbol();

            foreach ($etf->getHoldingCollection()->getAllETFHoldings() as $holding) {
                /** @var ETFHoldingCollection $holdingSymbol */
                $holdingSymbol = $holding->getHoldingSymbol();
                $holdingName = $holding->getHoldingName();
                $holdingPercentage = $holding->getAssets();

                $rowData = [$etfSymbol, $holdingSymbol, $holdingName, $holdingPercentage];
                $values[] = $rowData;
            }
        }
        return $values;
    }
}