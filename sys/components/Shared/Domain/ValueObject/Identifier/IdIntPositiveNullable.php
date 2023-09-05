<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject\Identifier;

use InvalidArgumentException;

class IdIntPositiveNullable extends IdAbstract implements IdInterface 
{
    //immutable
    protected readonly ?int $id;

    //self-validation
    protected function __construct(?int $id)
    {
        if ((!is_null($id) && $id <= 0)) {
            throw new InvalidArgumentException('Rules: id>0 or id==null.');
        }
        $this->id = $id;
    }

    public static function prepare($input): ?int
    {
        $res = intval($input);
        return ($res <= 0) ? null : $res;
    }

    public static function fromString(?string $string): static
    {
        $val = is_null($string) ? null : intval($string);
        return new static($val);
    }

    public function getId(): ?string
    {
        return is_null($this->id) ? null : (string) $this->id;
    }
}
