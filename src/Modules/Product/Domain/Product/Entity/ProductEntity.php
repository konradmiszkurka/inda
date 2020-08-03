<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Entity;

use App\Lib\Domain\BaseEntity;
use App\Modules\Product\Domain\Product\Interfaces\DataInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="product")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class ProductEntity extends BaseEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", name="name", length=128)
     */
    private $name;
    /**
     * @var int
     * @ORM\Column(type="integer", name="price")
     */
    private $price;
    /**
     * @var string
     * @ORM\Column(type="string", name="description")
     */
    private $description;

    public function __construct(DataInterface $data)
    {
        $this->populate($data);
    }

    public function update(DataInterface $data): void
    {
        $this->populate($data);
    }

    public function remove(): void
    {
        $this->removed();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function populate(DataInterface $data): void
    {
        $this->name     = $data->getName();
        $this->price    = $data->getPrice();
        $this->description = $data->getDescription();
    }
}