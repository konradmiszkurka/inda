<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Entity;

use App\Lib\Domain\BaseEntity;
use App\Modules\Product\Domain\Price\Entity\PriceEntity;
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
     * @var PriceEntity|null
     * @ORM\OneToOne(targetEntity="\App\Modules\Product\Domain\Price\Entity\PriceEntity")
     * @ORM\JoinColumn(name="price_id", nullable=true)
     */
    private $price;
    /**
     * @var string
     * @ORM\Column(type="text", name="description")
     */
    private $description;

    public function getPrice(): ?PriceEntity
    {
        return $this->price;
    }

    public function __construct(DataInterface $data)
    {
        $this->populate($data);
        $this->created();
    }

    public function update(DataInterface $data): void
    {
        $this->populate($data);
        $this->updated();
    }

    public function remove(): void
    {
        $this->removed();
    }

    public function getName(): string
    {
        return $this->name;
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