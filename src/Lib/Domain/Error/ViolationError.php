<?php
declare(strict_types=1);

namespace App\Lib\Domain\Error;

final class ViolationError extends Error
{
    /**
     * @var string
     */
    private $fieldName;

    public function __construct(string $code, string $message, string $fieldName)
    {
        parent::__construct($code, $message);
        $this->fieldName = $fieldName;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'fieldName' => $this->fieldName,
            'message' => $this->message,
        ];
    }
}