<?php
declare(strict_types=1);

namespace App\Lib\Infrastructure\Doctrine\Mocks;

use App\Modules\User\Domain\User\Enum\RoleEnum;
use App\Modules\User\Domain\User\Interfaces\UserDataInterface;

final class UserDataMock implements UserDataInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getUserName(): string
    {
        return $this->data['userName'] ?? sprintf('%sName', uniqid('', true));
    }

    public function getFirstName(): ?string
    {
        return $this->data['firstName'] ?? sprintf('%sFirstname', uniqid('', true));
    }

    public function getLastName(): ?string
    {
        return $this->data['lastName'] ?? sprintf('%sLastName', uniqid('', true));
    }

    public function getPassword(): string
    {
        return $this->data['password'] ?? 'password';
    }

    public function getEmail(): string
    {
        return $this->data['email'] ?? sprintf('%s@testowaDomena.pl', uniqid('', true));
    }

    public function getRole(): string
    {
        return $this->data['role'] ?? RoleEnum::ROLE_ADMIN;
    }
    public function getPhone(): ?string
    {
        return $this->data['phone'] ?? 'phone';
    }
}