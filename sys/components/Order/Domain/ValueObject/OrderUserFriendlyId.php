<?php

declare(strict_types=1);

namespace app\components\Order\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdAbstract;
use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use InvalidArgumentException;

/** @psalm-immutable */
class OrderUserFriendlyId extends IdAbstract implements IdInterface
{
    //immutable
    protected readonly ?string $id;

    //self-validation
    protected function __construct(?string $string)
    {
        if (!is_null($string) && !preg_match('/^БН\-\d{1,10}$/u', $string)) {
            throw new InvalidArgumentException('Id should be a OrderUserFriendlyId.');
        }
        $this->id = $string;
    }

    public static function fromString(?string $string): static
    {
        return new static($string);
    }

    public function getId(): ?string
    {
        return is_null($this->id) ? null : (string) $this->id;
    }
}
