<?php
declare(strict_types=1);

namespace App\Lib\Domain\Result;

use App\Lib\Domain\Error\Error;
use App\Lib\Domain\Error\Errors;
use App\Lib\Domain\Error\ViolationError;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Result implements ResultInterface
{
    /**
     * @var Errors|null
     */
    private $errors;
    /**
     * @var string|null
     */
    private $identifier = null;
    /**
     * @var object|null
     */
    private $object = null;

    public function __construct(?Errors $errors = null, ?int $identifier = null)
    {
        $this->errors = $errors ?? new Errors();
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getObject(): ?object
    {
        return $this->object;
    }

    public function addError(Error $error): void
    {
        $this->errors[] = $error;
    }

    public function addFromViolations(ConstraintViolationListInterface $violationList, string $fieldPrefix): void
    {
        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $this->errors[] = new ViolationError(
                $violation->getCode(),
                $violation->getMessage(),
                sprintf('%s.%s', $fieldPrefix, $violation->getPropertyPath())
            );
        }
    }

    /**
     * @var string|int|null $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = (string)$identifier;
    }

    public function setObject(?object $object): void
    {
        $this->object = $object;

        if ($object !== null) {
            $this->setIdentifier($object->getId());
        }
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !$this->isSuccessful();
    }

    public function isSuccessful(): bool
    {
        return !(bool)count($this->errors);
    }
}
