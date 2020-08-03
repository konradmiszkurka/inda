<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use JMS\Serializer\Annotation as Serializer;

final class Action
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $type;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $path;

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
