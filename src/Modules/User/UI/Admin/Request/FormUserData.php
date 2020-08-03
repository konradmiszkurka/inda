<?php

declare(strict_types=1);

namespace App\Modules\User\UI\Admin\Request;

use App\Modules\User\Domain\User\Interfaces\UserDataInterface;

final class FormUserData implements UserDataInterface
{
    /**
     * @var string|null
     */
    private $userName = null;
    /**
     * @var string|null
     */
    private $phone = null;
    /**
     * @var string|null
     */
    private $firstName = null;
    /**
     * @var string|null
     */
    private $lastName = null;
    /**
     * @var string|null
     */
    private $password = null;
    /**
     * @var string|null
     */
    private $email = null;
    /**
     * @var string|null
     */
    private $role = null;

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): FormUserData
    {
        $this->userName = $userName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): FormUserData
    {
        $this->phone = $phone;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): FormUserData
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): FormUserData
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): FormUserData
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): FormUserData
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): FormUserData
    {
        $this->role = $role;

        return $this;
    }
}