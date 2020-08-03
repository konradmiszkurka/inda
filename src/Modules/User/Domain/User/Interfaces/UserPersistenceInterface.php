<?php 
declare(strict_types=1);

namespace App\Modules\User\Domain\User\Interfaces;

use App\Modules\User\Domain\User\Entity\UserEntity;

interface UserPersistenceInterface
{
    public function persist(UserEntity $entity): void;

    public function flush(): void;
}