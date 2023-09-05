<?php

declare(strict_types=1);

namespace app\components\Message\Domain\Entity;

use app\components\Message\Domain\ValueObject\MessageId;
use app\components\Message\Domain\ValueObject\Phone;
use app\components\Message\Domain\ValueObject\Txt;
use app\components\Message\Domain\ValueObject\Url;
use app\components\Message\Domain\ValueObject\Username;
use app\components\Shared\Domain\ValueObject\Email;
use DateTimeImmutable;
use InvalidArgumentException;

class Message
{
    private function __construct(
        private MessageId $id,
        private Username $username,
        private Email $email,
        private Phone $phone,
        private Txt $txt,
        private DateTimeImmutable $createdAt,
        private Url $url,
    ) {
    }

    public static function new(
        Username $username,
        Email $email,
        Phone $phone,
        Txt $txt,
        DateTimeImmutable $createdAt,
        Url $url,
    ): self {
        return new self(
            id: MessageId::fromString(null),
            username: $username,
            email: $email,
            phone: $phone,
            txt: $txt,
            createdAt: $createdAt,
            url: $url,
        );
    }

    public function appendId(
        MessageId $id,
    ): self {
        return new self(
            id: $id,
            username: $this->getUsername(),
            email: $this->getEmail(),
            phone: $this->getPhone(),
            txt: $this->getTxt(),
            createdAt: $this->getCreatedAt(),
            url: $this->getUrl(),
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTxt()
    {
        return $this->txt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
