<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject\Identifier;

/*
        =Domain=
            Uses the concept of ID (IdInterface)
            The specific implementation and what is inside the ID does not matter.

        =Infrastructure:Repository=
        Decides what the ID class will be and what it contains inside.
    */
/*
        1. Easier testing; injecting the ID as a dependency allows you to create the entity in a known state
        2. Injecting the ID allows the repository to recreate an entity from storage
        3. Injecting the ID allows for easy switching between generators. v4 UUID v5 
        4. Identifier re-use: The ID might be shared between processes.
        5. Domain code is about business logic; Id generation is a technical detail. Generating the Id outside the domain keeps the domain clean. 
    */
/*
        INTEGER => Make a table with a single auto-incremental id field and use that to get nextId.
        ? or Insert dummy-record, get Id. Then Update dummy-record to real data.
    */

interface IdInterface
{
    public static function fromString(?string $string): IdInterface;    
    public function isEqualsTo(IdInterface $id): bool;
    public function getId(): ?string;
}
