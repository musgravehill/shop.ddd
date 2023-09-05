<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject\Identifier;

use InvalidArgumentException;

use Ramsey\Uuid\Uuid;

class IdUUIDv7 extends IdAbstract implements IdInterface
{
    //immutable
    protected readonly ?string $id;

    //self-validation
    protected function __construct(?string $string)
    {
        if (!is_null($string) && mb_strlen($string, "utf-8") > 36) {
            throw new InvalidArgumentException('Rule: id L <= 36.');
        }
        if (!is_null($string) && !Uuid::isValid($string)) {
            throw new InvalidArgumentException('Id should be a Ramsey\Uuid\Uuid.');
        }
        $this->id = $string;
    }

    public static function prepare($input): ?string
    {
        $res = strval($input);
        return (!isset($res[20])) ? null : $res;
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
