<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User;

use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Exception\UserAlreadyExists;
use App\Modules\User\Domain\User\Interfaces\ChangePasswordDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserAccountDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserPersistenceInterface;
use App\Modules\User\Domain\User\Interfaces\UserRepositoryInterface;

class UserService
{
    /**
     * @var UserPersistenceInterface
     */
    private $persistence;
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserPersistenceInterface $persistence, UserRepositoryInterface $repository)
    {
        $this->persistence = $persistence;
        $this->repository = $repository;
    }

    public function create(UserDataInterface $data): UserEntity
    {
        $alreadyExists = $this->repository->findByEmail($data->getEmail()) !== null;
        if ($alreadyExists) {
            throw new UserAlreadyExists(sprintf('User with email:%s already exists', $data->getEmail()));
        }
        $entity = new UserEntity();
        $entity->create($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function update(UserEntity $entity, UserDataInterface $data): UserEntity
    {
        $emailChanged = $entity->getEmail() !== $data->getEmail();
        if ($emailChanged) {
            $alreadyExists = $this->repository->findByEmail($data->getEmail()) !== null;
            if ($alreadyExists) {
                throw new UserAlreadyExists(sprintf('User with email:%s already exists', $data->getEmail()));
            }
        }

        $entity->update($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function remove(UserEntity $entity): bool
    {
        $entity->remove();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return true;
    }

    public function activate(UserEntity $entity): UserEntity
    {
        $entity->activate();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function deactivate(UserEntity $entity): UserEntity
    {
        $entity->deactivate();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function addFile(UserEntity $entity, object $fileObject): UserEntity
    {
        $entity->addFile($fileObject);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function updateAccount(UserEntity $entity, UserAccountDataInterface $data): UserEntity
    {
        $emailChanged = $entity->getEmail() !== $data->getEmail();
        if ($emailChanged) {
            $alreadyExists = $this->repository->findByEmail($data->getEmail()) !== null;
            if ($alreadyExists) {
                throw new UserAlreadyExists(sprintf('User with email:%s already exists', $data->getEmail()));
            }
        }

        $entity->updateAccount($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function updateAvatarTypeAccount(UserEntity $entity, string $avatarType): UserEntity
    {
        $entity->updateAvatarTypeAccount($avatarType);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function changePasswordAccount(UserEntity $entity, ChangePasswordDataInterface $data): UserEntity
    {
        $entity->changePasswordAccount($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }
}