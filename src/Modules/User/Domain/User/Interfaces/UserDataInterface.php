<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User\Interfaces;

use Symfony\Component\Validator\Constraints as Assert;

interface UserDataInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="64")
     */
    public function getUsername(): ?string;

    /**
     * @Assert\Length(min="9", max="11")
     */
    public function getPhone(): ?string;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="64")
     */
    public function getFirstName(): ?string;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="64")
     */
    public function getLastName(): ?string;

    /**
     * @Assert\Length(min="1", max="256")
     */
    public function getPassword(): ?string;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    public function getEmail(): ?string;

    /**
     * @Assert\NotBlank()
     */
    public function getRole(): ?string;
}