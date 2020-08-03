<?php
declare(strict_types=1);

namespace App\Lib\Domain\User;

interface UserEntityInterface
{
    public function getId(): int;

    public function getEmail();
}