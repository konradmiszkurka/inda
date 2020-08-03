<?php
declare(strict_types=1);

namespace App\Modules\User\Domain\User\Interfaces;

use Symfony\Component\Validator\Constraints as Assert;

interface ChangePasswordDataInterface
{
    /**
     * @Assert\NotBlank()
     */
    public function getOldPassword(): ?string;

    /**
     * @Assert\Length(min="1", max="256")
     */
    public function getPassword1(): ?string;

    /**
     * @Assert\Length(min="1", max="256")
     */
    public function getPassword2(): ?string;
}