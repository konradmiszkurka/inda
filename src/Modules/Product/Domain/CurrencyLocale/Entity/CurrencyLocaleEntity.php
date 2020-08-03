<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\CurrencyLocale\Entity;

use App\Lib\Domain\BaseEntity;
use App\Modules\Product\Domain\CurrencyLocale\Interfaces\DataInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency_locale")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class CurrencyLocaleEntity extends BaseEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", name="locale", length=128)
     */
    private $locale;
    /**
     * @var string
     * @ORM\Column(type="string", name="value")
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

    public function getLocale()
    {
        return $this->locale;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function populate(DataInterface $data): void
    {
        $this->value = $data->getValue();
        $this->locale = $data->getLocale();
    }
}