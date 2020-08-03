<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use App\Lib\Domain\Collection\AbstractCollection;
use JMS\Serializer\Annotation as Serializer;

final class Elements extends AbstractCollection
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $name;

    public function getType(): string
    {
        return Element::class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Elements
    {
        $this->name = $name;
        return $this;
    }
}
