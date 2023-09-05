<?php

declare(strict_types=1);

namespace app\components\User\Domain\Entity;

use app\components\Shared\Domain\ValueObject\Email;
use app\components\User\Domain\ValueObject\Address;
use app\components\User\Domain\ValueObject\CityName;
use app\components\User\Domain\ValueObject\Phone;
use app\components\User\Domain\ValueObject\UserId;
use app\components\User\Domain\ValueObject\Username;
use DateTimeImmutable;
use InvalidArgumentException;

/** @psalm-immutable */
final class User
{
    //self-validation
    private function __construct(
        private readonly UserId $id,
        private readonly Username $username,
        private readonly Email $email,
        private readonly Phone $phone,
        private readonly DateTimeImmutable $createdAt,
        private readonly CityName $cityName,
        private readonly Address $address,
    ) {
    }

    public static function registerUser(
        Email $email
    ): self {
        return new self(
            id: UserId::fromString(null),
            username: Username::dummy(),
            email: $email,
            phone: Phone::dummy(),
            createdAt: new DateTimeImmutable(),
            cityName: CityName::dummy(),
            address: Address::dummy(),
        );
    }

    public static function hydrateExisting(
        UserId $id,
        Username $username,
        Email $email,
        Phone $phone,
        DateTimeImmutable $createdAt,
        CityName $cityName,
        Address $address,
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException('Rule: id not null.');
        }
        return new self(
            id: $id,
            username: $username,
            email: $email,
            phone: $phone,
            createdAt: $createdAt,
            cityName: $cityName,
            address: $address,
        );
    }

    public function changePersonalDataCustomer(
        Username $username,
        Phone $phone,
        CityName $cityName,
        Address $address,
    ): self {
        return new self(
            id: $this->getId(),
            username: $username,
            email: $this->getEmail(),
            phone: $phone,
            createdAt: $this->getCreatedAt(),
            cityName: $cityName,
            address: $address,
        );
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCityName(): CityName
    {
        return $this->cityName;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
}
