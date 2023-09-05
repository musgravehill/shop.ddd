<?php

declare(strict_types=1);

namespace app\components\Order\Domain\ValueObject;

use app\components\Delivery\Domain\ValueObject\DeliveryTypeId;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class DeliveryParams extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly DeliveryTypeId $deliveryTypeId,
        private readonly string $cityName
    ) {
        if (!preg_match('/^[\w\d\s\+\-\?\$\.\*\(\)\[\],~!@#№%&=:;<>_\/"]{0,512}$/u', $cityName)) {
            throw new InvalidArgumentException('Rule: cityName should be simple text 0..512.');
        }
    }

    public static function prepareСityName($str): ?string
    {
        $str = strip_tags($str);
        $str = $str ? str_replace(array('&laquo;', '&raquo;', '«', '»', "'", '&quot;', '`'), '"', $str) : '';
        $str = $str ? str_replace(array('—', '—', '–', '−', '-'), '-', $str) : '';
        $str = preg_replace('/[^\w\d\s\+\-\?\$\.\*\(\)\[\],~!@#№%&=:;<>_\/"]/Uui', ' ', $str);
        $str = preg_replace('/ {2,}/Uui', ' ', $str);
        $str = $str ? str_replace(array("\r", "\n", "\t"), '', $str) : '';
        $str = trim($str);
        $str = mb_substr($str, 0, 512, "UTF-8");
        return $str;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->deliveryTypeId, $this->cityName])); //you can add params (color white-red, size S-M-L) to array
    }

    //structural equality, compare      
    public function isEqualsTo(ValueObjectInterface $vo): bool
    {
        parent::isEqualsTo($vo);
        /** @var self $vo */
        if ($this->getStructuralEqualityIdentifier() !== $vo->getStructuralEqualityIdentifier()) {
            return false;
        }
        return true;
    }

    public function getDeliveryTypeId(): DeliveryTypeId
    {
        return $this->deliveryTypeId;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }
}
