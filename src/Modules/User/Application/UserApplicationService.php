<?php

declare(strict_types=1);

namespace App\Modules\User\Application;

use App\Lib\Domain\Error\Error;
use App\Lib\Domain\Result\Result;
use App\Lib\Domain\Result\ResultInterface;
use App\Lib\Application\Error\ErrorCodes;
use App\Modules\Attachment\Application\PhotoApplicationService;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Exception\UserAlreadyExists;
use App\Modules\User\Domain\User\Interfaces\ChangePasswordDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserAccountDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserDataInterface;
use App\Modules\User\Domain\User\ListUserDataTable;
use App\Modules\User\Domain\User\UserService;
use App\Modules\User\UI\Admin\Request\PhotoData;
use Omines\DataTablesBundle\DataTable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserApplicationService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserService
     */
    private $service;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;
    /**
     * @var ListUserDataTable
     */
    private $usersDataTable;

    public function __construct(
        ValidatorInterface $validator,
        UserService $service,
        EncoderFactoryInterface $encoderFactory,
        ListUserDataTable $usersDataTable
    ) {
        $this->validator = $validator;
        $this->service = $service;
        $this->encoderFactory = $encoderFactory;
        $this->usersDataTable = $usersDataTable;
    }

    public function createUser(UserDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), 'user');

        if ($result->hasErrors()) {
            return $result;
        }

        try {
            $user = $this->service->create($data);
            $result->setObject($user);
        } catch (UserAlreadyExists $e) {
            $result->addError(new Error(ErrorCodes::USER_ALREADY_EXISTS, $e->getMessage()));
        }

        return $result;
    }

    public function updateUser(UserEntity $entity, UserDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), 'user');

        try {
            $this->service->update($entity, $data);
        } catch (UserAlreadyExists $e) {
            $result->addError(new Error(ErrorCodes::USER_EMAIL_OCCUPIED, $e->getMessage()));
        }

        return $result;
    }

    public function activeUser(UserEntity $entity): ResultInterface
    {
        $result = new Result();

        $this->service->activate($entity);

        return $result;
    }

    public function deactivateUser(UserEntity $entity): ResultInterface
    {
        $result = new Result();

        $this->service->deactivate($entity);

        return $result;
    }

    public function removeUser(UserEntity $entity): ResultInterface
    {
        $result = new Result();

        $this->service->remove($entity);

        return $result;
    }

    public function updateAccount(UserEntity $entity, UserAccountDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), 'user_account');

        try {
            $this->saveAvatarFile($data);
            $this->service->updateAccount($entity, $data);
        } catch (UserAlreadyExists $e) {
            $result->addError(new Error(ErrorCodes::USER_EMAIL_OCCUPIED, $e->getMessage()));
        }

        return $result;
    }

    public function changeAvatarTypeAccount(UserEntity $entity, string $avatarType): ResultInterface
    {
        $result = new Result();

        $this->service->updateAvatarTypeAccount($entity, $avatarType);

        return $result;
    }

    public function changePasswordAccount(UserEntity $entity, ChangePasswordDataInterface $data): ResultInterface
    {
        $result = new Result();
        $encoder = $this->encoderFactory->getEncoder($entity);

        if ($encoder->isPasswordValid($entity->getPassword(), $data->getOldPassword(), $entity->getSalt()) === false) {
            $result->addError(new Error(ErrorCodes::USER_PASSWORD_OLD_INCORRECT, 'Old password is incorrect.'));
        }

        if ($data->getPassword1() !== $data->getPassword2()) {
            $result->addError(new Error(ErrorCodes::USER_PASSWORD_DONT_MATCH, 'Password is not identical'));
        }

        if ($result->hasErrors()) {
            return $result;
        }

        $this->service->changePasswordAccount($entity, $data);

        return $result;
    }

    private function saveAvatarFile(UserAccountDataInterface $data): void
    {
        if ($data->getAvatarFileNew() instanceof UploadedFile) {
            $file = $this->photoApplication->createPhoto(
                (new PhotoData())
                    ->setName('avatar')
                    ->setFile($data->getAvatarFileNew())
            );

            $data->setAvatarFile($file->getObject());
        }
    }

    public function getUsersListDataTable(): DataTable
    {
        $table = $this->usersDataTable->create();

        return $this->usersDataTable->adapter($table);
    }
}