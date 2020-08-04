<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Request;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use App\Modules\Product\Domain\Product\Interfaces\DataInterface;
use App\Modules\Product\Domain\Product\Interfaces\DataInterfaceRequest;

final class FormProductWithPriceData implements DataInterfaceRequest
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
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
     * @return FormProductWithPriceData
     */
    public function setName(?string $name): FormProductWithPriceData
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string|null $price
     * @return FormProductWithPriceData
     */
    public function setPrice(?string $price): FormProductWithPriceData
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
     * @return FormProductWithPriceData
     */
    public function setDescription(?string $description): FormProductWithPriceData
    {
        $this->description = $description;
        return $this;
    }

}