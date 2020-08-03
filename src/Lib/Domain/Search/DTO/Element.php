<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use JMS\Serializer\Annotation as Serializer;

final class Element
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
    private $title;
    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $text;
    /**
     * @var Date|null
     * @Serializer\Type("App\Lib\Domain\Search\DTO\Date")
     */
    private $date;
    /**
     * @var Actions|null
     * @Serializer\Type("App\Lib\Domain\Search\DTO\Actions")
     */
    private $actions;
    /**
     * @var Icons|null
     * @Serializer\Type("App\Lib\Domain\Search\DTO\Icons")
     */
    private $icons;
    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $template;

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getDate(): ?Date
    {
        return $this->date;
    }

    public function getActions(): ?Actions
    {
        return $this->actions;
    }

    public function getIcons(): ?Icons
    {
        return $this->icons;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }
}
