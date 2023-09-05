<?php

declare(strict_types=1);

namespace app\components\SupplierImport\Parser;

use app\components\HelperY;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\SupplierImport\Contract\ParserInterface;
use Exception;
use Generator;
use Yii;
//
use OpenSpout\Reader\XLSX\Reader;

use function PHPUnit\Framework\returnSelf;

//DSSL 
class Parser_0188f85ca3ff72edbcc72e1d8cffc836 implements ParserInterface
{
    const PRICELIST_URL = 'https://price.dssl.ru/Прайс-лист%20дистрибьютор_%20все%20склады%20(XLSX).xlsx';
    const WORKDIR = '@webroot/log';

    /*
    0___
1___Производитель
2___
3___Код
4___Артикул
5___
6___Номенклатура
7___Единица хранения
8___Статус
9___Валюта
10___Розница
11___СТОП-цена
12___Интегратор
13___Дилер
14___Дистрибьютер
15___Вес
16___Единица измерения
17___Объем
18___Единица измерения
19___Описание
20___Итого
21___МСК
22___СПБ
23___КРД
24___НН
25___НСК
26___ВЛК
27___ВЛГ
28___ВРЖ
29___ЕКБ
30___КЗН
31___Пермь
32___РНД
33___САМ
34___УФА
35___ЯР
36___Пятигорск
37___Сургут
38___Красноярск
39___Удаленный склад
    */

    public function run(): Generator
    {
        $filePathFull = $this->getFilePathFull();

        if (!$this->downloadDistantFile($filePathFull)) {
            throw new Exception('Not downloadDistantFile');
        }

        $i = 0;
        $reader = new Reader();
        $reader->open($filePathFull);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $i++;
                //  if ($i > 60) {
                //       break;
                //  }

                $cells = $row->getCells();
                if (!isset($cells[10]) || floatval($cells[10]->getValue()) <= 0) { // priceSell
                    continue;
                }
                if (!isset($cells[20]) || intval($cells[20]->getValue()) <= 0) { // qty
                    continue;
                }

                $brandNameRaw = $cells[1]->getValue();
                $skuRaw = $cells[3]->getValue();
                $nameShortRaw = $cells[4]->getValue();
                $nameFullRaw = $cells[6]->getValue();
                $currencyNameRaw = $cells[9]->getValue();
                $priceSellRaw = $cells[10]->getValue();
                $pricePurchRaw = $cells[14]->getValue();
                $dscRaw = $cells[19]->getValue();
                $qtyRaw = $cells[20]->getValue();

                $priceSelling = new Money(
                    fractionalCount: intval(abs(100 * floatval($priceSellRaw))),
                    currency: $this->mapCurrency($currencyNameRaw)
                );
                $pricePurchase = new Money(
                    fractionalCount: intval(abs(100 * floatval($pricePurchRaw))),
                    currency: $this->mapCurrency($currencyNameRaw)
                );
                $qty = new QuantityZeroPositive(intval(abs(intval($qtyRaw))));

                yield new ParserItemDto(
                    sku: $skuRaw,
                    brandName: $brandNameRaw,
                    name: $nameFullRaw,
                    dsc: $dscRaw,
                    quantityAvailable: $qty,
                    priceSelling: $priceSelling,
                    pricePurchase: $pricePurchase,
                    imgUrl: null
                );
            }
        }
        $reader->close();

        $this->unlinkLocalPricelistFile($filePathFull);
    }

    private function mapCurrency(string $currencyNameRaw): ?MoneyСurrency
    {
        switch ($currencyNameRaw) {
            case 'руб.':
                return MoneyСurrency::RUB;
                break;
            case 'USD':
                return MoneyСurrency::USD;
                break;
            case 'EUR':
                return MoneyСurrency::EUR;
                break;
        }
        return null;
    }

    private function getFilePathFull(): string
    {
        $workdir = Yii::getAlias(self::WORKDIR);
        return $workdir . '/' . md5(self::PRICELIST_URL) . '.prc';
    }

    private function downloadDistantFile(): bool
    {
        $filePathFull = $this->getFilePathFull();
        if (!$filePathFull) {
            return false;
        }
        $options = array(
            CURLOPT_FILE => is_resource($filePathFull) ? $filePathFull : fopen($filePathFull, 'w'),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_URL => self::PRICELIST_URL,
            CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $return = curl_exec($ch);

        if ($return === false) {
            return false;
        } else {
            return true;
        }
    }

    private function unlinkLocalPricelistFile(): void
    {
        $filePathFull = $this->getFilePathFull();
        if (!$filePathFull) {
            return;
        }
        if (strpos($filePathFull, '.prc') && file_exists($filePathFull)) {
            @unlink($filePathFull);
        }
    }
}
