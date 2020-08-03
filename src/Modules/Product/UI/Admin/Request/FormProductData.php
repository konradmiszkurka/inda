<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Request;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use App\Modules\Product\Domain\Product\Interfaces\DataInterface;

final class FormProductData implements DataInterface
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var PriceEntity|null
     */
    private $price;
    /**
     * @var string|null
     */
    private $description;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return FormProductData
     */
    public function setName(?string $name): FormProductData
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return PriceEntity|null
     */
    public function getPrice(): ?PriceEntity
    {
        return $this->price;
    }

    /**
     * @param PriceEntity|null $price
     * @return FormProductData
     */
    public function setPrice(?PriceEntity $price): FormProductData
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return FormProductData
     */
    public function setDescription(?string $description): FormProductData
    {
        $this->description = $description;
        return $this;
    }

}