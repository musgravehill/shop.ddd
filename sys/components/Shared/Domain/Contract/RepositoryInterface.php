<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\Contract;

use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;

interface RepositoryInterface
{    
    public function nextId(): IdInterface;
}
