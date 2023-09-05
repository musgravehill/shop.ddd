<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Model\ValueObject;
 
enum RoleTypeId: int 
{
    case Customer = 10;
    case Admin = 20;    
}
