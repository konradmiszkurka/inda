<?php
declare(strict_types=1);

namespace App\Lib\Domain\Result;

use App\Lib\Domain\Error\Errors;

interface ResultInterface
{
    public function getIdentifier(): ?string;

    public function getObject(): ?object;

    public function getErrors(): Errors;

    public function isSuccessful(): bool;
}
