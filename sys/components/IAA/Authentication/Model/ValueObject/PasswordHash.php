<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Model\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class PasswordHash extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $passwordHash
    ) {
        if (mb_strlen($passwordHash, "UTF-8") !== 60) {
            throw new InvalidArgumentException('Rule: passwordHash.L === 64');
        }

        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $passwordHash, $matches) || $matches[1] < 4 || $matches[1] > 30) {
            throw new InvalidArgumentException('Rule: passwordHash.L === 64');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->passwordHash])); //you can add params (color white-red, size S-M-L) to array
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

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}
