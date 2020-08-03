<?php
declare(strict_types=1);

namespace App\Modules\User\UI\Admin\Request;

use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Interfaces\ChangePasswordDataInterface;

final class FormChangePasswordData implements ChangePasswordDataInterface
{
    /**
     * @var string|null
     */
    private $oldPassword = null;
    /**
     * @var string|null
     */
    private $password1 = null;
    /**
     * @var string|null
     */
    private $password2 = null;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): FormChangePasswordData
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getPassword1(): ?string
    {
        return $this->password1;
    }

    public function setPassword1(?string $password1): FormChangePasswordData
    {
        $this->password1 = $password1;

        return $this;
    }

    public function getPassword2(): ?string
    {
        return $this->password2;
    }

    public function setPassword2(?string $password2): FormChangePasswordData
    {
        $this->password2 = $password2;

        return $this;
    }
}