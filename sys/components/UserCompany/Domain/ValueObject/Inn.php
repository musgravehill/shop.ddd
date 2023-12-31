<?php

declare(strict_types=1);

namespace app\components\UserCompany\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Inn extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $inn
    ) {           
        if (!preg_match('/^[\d]{10,12}$/u', $inn)) {
            throw new InvalidArgumentException('Rule: inn should be simple d 10, 12.');
        }
        if (!$this->isInn($inn)) {
            throw new InvalidArgumentException('Rule: inn should be simple d 10, 12.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->inn])); //you can add params (color white-red, size S-M-L) to array
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

    public function getInn(): string
    {
        return $this->inn;
    }

    public function isInn($inn)
    {
        $len = strlen($inn);

        if ($len === 10) {
            return $inn[9] === (string) (((2 * $inn[0] + 4 * $inn[1] + 10 * $inn[2] +
                3 * $inn[3] + 5 * $inn[4] + 9 * $inn[5] +
                4 * $inn[6] + 6 * $inn[7] + 8 * $inn[8]
            ) % 11) % 10);
        } elseif ($len === 12) {
            $num10 = (string) (((7 * $inn[0] + 2 * $inn[1] + 4 * $inn[2] +
                10 * $inn[3] + 3 * $inn[4] + 5 * $inn[5] +
                9 * $inn[6] + 4 * $inn[7] + 6 * $inn[8] +
                8 * $inn[9]
            ) % 11) % 10);

            $num11 = (string) (((3 * $inn[0] + 7 * $inn[1] + 2 * $inn[2] +
                4 * $inn[3] + 10 * $inn[4] + 3 * $inn[5] +
                5 * $inn[6] + 9 * $inn[7] + 4 * $inn[8] +
                6 * $inn[9] + 8 * $inn[10]
            ) % 11) % 10);

            return $inn[11] === $num11 && $inn[10] === $num10;
        }

        return false;
    }
}
