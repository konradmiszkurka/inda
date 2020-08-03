<?php
declare(strict_types=1);

namespace App\Lib\Domain\Error;

use JsonSerializable;

class Error implements JsonSerializable
{
    /**
     * @var string
     */
    protected $code;
    /**
     * @var string
     */
    protected $message;

    public function __construct(string $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}
