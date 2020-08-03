<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use JMS\Serializer\Annotation as Serializer;

final class Icon
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $uri;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $icon;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $title;

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
