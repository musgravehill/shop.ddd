<?php

declare(strict_types=1);

namespace app\components\IAA\AccessRecovery;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class AccessRecoveryToken
{
    //immutable
    protected readonly string $token;

    //self-validation
    protected function __construct(string $string)
    {
        if (!Uuid::isValid($string)) {
            throw new InvalidArgumentException('Id should be a Ramsey\Uuid\Uuid.');
        }
        $this->token = $string;
    }

    //structural equality, compare
    public function isEqualsTo(AccessRecoveryToken $id): bool
    {
        if ($this->getToken() !== $id->getToken()) {
            return false;
        }
        return true;
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public static function new(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getToken(): string
    {
        return (string) $this->token;
    }
}
