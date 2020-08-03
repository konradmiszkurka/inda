<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Price\Entity;

use App\Lib\Domain\BaseEntity;
use App\Modules\Product\Domain\Price\Interfaces\DataInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="price")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class PriceEntity extends BaseEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", name="currency", length=128)
     */
    private $currency;
    /**
     * @var string
     * @ORM\Column(type="float", name="value")
     */
    private $value;

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

   public function getCurrency()
   {
       return $this->currency;
   }

   public function getValue()
   {
       return $this->value;
   }

    public function populate(DataInterface $data): void
    {
        $this->value = $data->getValue();
        $this->currency = $data->getCurrency();
    }
}