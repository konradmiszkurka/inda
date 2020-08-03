<?php
declare(strict_types=1);

namespace App\Lib\Domain\Transform;

use ReflectionClass;
use ReflectionProperty;

abstract class AbstractTransform
{
    /**
     * @var string 
     */
    protected $exitClassName;
    /**
     * @var object 
     */
    protected $exitClass;

    abstract public function getType(): string;

    public function __construct(string $exitClass)
    {
        $this->exitClassName = $exitClass;
        $this->exitClass = new $exitClass();
    }

    protected function getVariables(): array
    {
        try {
            $reflection = new ReflectionClass($this->exitClassName);
            return $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        } catch (\ReflectionException $e) {
            return [];
        }
    }
}
