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
//
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;
use Yii;

//SDEALER 
class Parser_0189b114762972b398bec03b37dd89c2 implements ParserInterface
{
    const PRICELIST_URL = 'http://sd.teamtimer.ru/prc/bn-bzn.xml';
    const WORKDIR = '@webroot/log';

    public function run(): Generator
    {
        $filePathFull = $this->getFilePathFull();

        if (!$this->downloadDistantFile($filePathFull)) {
            throw new Exception('Not downloadDistantFile');
        }

        $stream = new Stream\File($filePathFull, 1024); //$CHUNK_SIZE
        $options = array(
            'uniqueNode' => 'offer',
        );
        $parser = new Parser\UniqueNode($options);
        $streamer = new XmlStringStreamer($parser, $stream);

        $i = 0;
        while ($node = $streamer->getNode()) {
            $i++;
            // if ($i > 20) {
            //    break;
            // }
            $data = simplexml_load_string($node);

            if (floatval($data->price) <= 0 || intval($data->count) <= 0) {
                continue;
            }

            $id = (string) $data->attributes()->id;
            $available = $data->attributes()->available ? true : false;
            $price = new Money(
                fractionalCount: intval(abs(100 * floatval($data->price))),
                currency: MoneyСurrency::RUB
            );
            $count = new QuantityZeroPositive(intval(abs(intval($data->count))));
            $name = (string) $data->name;
            $vendor = (string) $data->vendor;
            $description = (string) $data->description;
            $imgUrl = null; // (string) $data->picture;            

            yield new ParserItemDto(
                sku: $id,
                brandName: $vendor,
                name: $name,
                dsc: $description,
                quantityAvailable: $count,
                priceSelling: $price,
                pricePurchase: $price,
                imgUrl: $imgUrl
            );
        }

        $this->unlinkLocalPricelistFile($filePathFull);
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
