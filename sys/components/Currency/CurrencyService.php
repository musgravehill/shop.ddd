<?php

declare(strict_types=1);

namespace app\components\Currency;

use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;

class CurrencyService
{
    private readonly array $data;

    public function __construct()
    {
        /* "Valute": {
            "AUD": {
                "ID": "R01010",
                "NumCode": "036",
                "CharCode": "AUD",
                "Nominal": 1,
                "Name": "Австралийский доллар",
                "Value": 56.5472,
                */

        $url = 'https://www.cbr-xml-daily.ru/daily_json.js';
        $jsonString = file_get_contents($url);
        $raw = json_decode($jsonString, true);
        $data = [];
        foreach ($raw['Valute'] as $item) {
            $data[$item['NumCode']] = floatval($item['Value']);
        }
        $this->data = $data;
    }

    public function convertToRub(Money $money): Money
    {
        $moneyСurrency = $money->getСurrency();
        if ($moneyСurrency === MoneyСurrency::RUB) {
            return $money;
        }

        if (isset($this->data[$moneyСurrency->value])) {
            $fractionalCount = intval(round($money->getFractionalCount() * $this->data[$moneyСurrency->value]));
            return new Money(
                fractionalCount: $fractionalCount,
                currency: MoneyСurrency::RUB
            );
        }

        return new Money(
            fractionalCount: PHP_INT_MAX,
            currency: MoneyСurrency::RUB
        );
    }
}
