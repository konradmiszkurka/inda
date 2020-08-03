<?php

declare(strict_types=1);

namespace App\Lib\Domain\Collection;

use ArrayIterator;
use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;
use Traversable;

abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var array
     * @Serializer\Type("array")
     */
    protected $data = [];
    /**
     * @var int|null
     * @Serializer\Type("integer")
     */
    private $total;
    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    public $dataMapping;

    abstract public function getType(): string;

    public function __construct(?array $elements = null, ?int $total = null, ?string $dataMapping = null)
    {
        $elements = $elements ?? [];
        foreach ($elements ?? [] as $element) {
            if (false === $this->isValidType($element)) {
                throw new InvalidArgumentException('Collection can store only elements with type: ' . $this->getType());
            }
        }
        $this->data = $elements;
        $this->total = $total;
        $this->dataMapping = $dataMapping;
    }

    public function getTotal(): int
    {
        return $this->total ?? $this->count();
    }

    public function offsetSet($offset, $value): void
    {
        if (false === $this->isValidType($value)) {
            throw new InvalidArgumentException('Collection can store only elements with type: ' . $this->getType());
        }

        if (null === $offset) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    protected function isValidType($element): bool
    {
        $result = false;
        if (is_object($element)) {
            $result = get_class($element) === $this->getType() || is_subclass_of($element, $this->getType());
        }
        if (is_scalar($element)) {
            $result = gettype($element) === $this->getType();
        }
        if (is_array($element)) {
            $result = 'array' === strtolower($this->getType());
        }

        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function has(callable $compare): bool
    {
        $has = false;
        foreach ($this->data as $datum) {
            $has = $compare($datum);
            if ($has === true) {
                return $has;
            }
        }

        return $has;
    }
}
