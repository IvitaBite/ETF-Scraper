const puppeteer = require('puppeteer');

async function fetchETFHoldingData(url) {
    try {
        const browser = await puppeteer.launch({headless: false});
        const page = await browser.newPage();

        await page.goto(url);
        await page.waitForSelector('td[data-th="Symbol"]');

        const etfData = await page.$$eval('td[data-th="Symbol"] a',  symbols => {
            return symbols.map(symbol => {
                const etfSymbol = symbol.innerText;
                const symbolHref = symbol.getAttribute('href');
                const etfUrl = `https://etfdb.com${symbolHref}#holdings`;

                return {
                    etfSymbol,
                    symbolHref,
                    etfUrl,
                }
            });
        });

        const result = {
            etfSymbols: etfData.map(data => data.etfSymbol),
            holdingCollection: {},
        }

        for (const { etfSymbol, symbolHref, etfUrl } of etfData) {
            await page.goto(etfUrl);
            await page.waitForSelector('#etf-holdings > tbody > tr:nth-child(1) td[data-th="Symbol"]');

            result.holdingCollection[etfSymbol] = await Promise.all(Array.from({ length: 3 }, async (_, i) => {
                const holdingSymbol = await page.$eval(
                    `#etf-holdings > tbody > tr:nth-child(${i + 1}) td[data-th="Symbol"]`,
                    symbol => symbol.textContent.trim()
                ).catch(() => 'N/A');

                const holdingName = await page.$eval(
                    `#etf-holdings > tbody > tr:nth-child(${i + 1}) td[data-th="Holding"]`,
                    name => name.textContent.trim()
                ).catch(() => 'N/A');

                const assets = await page.$eval(
                    `#etf-holdings > tbody > tr:nth-child(${i + 1}) td[data-th="% Assets"]`,
                    name => name.textContent.trim()
                ).catch(() => 'N/A');

                return {
                    holdingSymbol,
                    holdingName,
                    assets,
                };
            }));
        }
        await browser.close();

        return JSON.stringify(result);

    } catch (error) {
        return JSON.stringify({ error: error.message })
    }
}

const url = 'https://etfdb.com/screener/#page=1&fifty_two_week_start=47.4&five_ytd_start=0.96';
fetchETFHoldingData(url).then(jsonResult => console.log(jsonResult));