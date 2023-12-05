<?php

declare(strict_types=1);

use App\Services\Google\SaveToSpreadsheetService;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$serviceAccountKeyPath = $_ENV['SERVICE_ACCOUNT_KEY_PATH'];

$spreadSheetID = $_ENV['GOOGLE_SPREADSHEET_ID'];
$range = 'Sheet1';

$url = 'https://etfdb.com/screener/#page=1&fifty_two_week_start=47.4&five_ytd_start=0.96';

$etfCollection = new SaveToSpreadsheetService();
$etfCollection->execute($url, $spreadSheetID, $range);