<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure;

use App\Lib\Domain\Repository\AbstractRepository;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Interfaces\UserPersistenceInterface;
use App\Modules\User\Domain\User\Interfaces\UserRepositoryInterface;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface, UserPersistenceInterface
{
    public function persist(UserEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function getEntityFQN(): string
    {
        return UserEntity::class;
    }

    public function find(int $id): ?UserEntity
    {
        return $this->repository->find($id);
    }

    public function findByEmail(string $email): ?UserEntity
    {
        return $this->repository->findOneBy(['email' => $email]);
    }
}