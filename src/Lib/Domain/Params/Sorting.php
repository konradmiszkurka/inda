<?php
declare(strict_types=1);

namespace App\Lib\Domain\Params;

use App\Lib\Domain\Sorting\SortingInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final class Sorting implements SortingInterface
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Assert\NotBlank()
     */
    private $field;
    /**
     * @var string
     * @Serializer\Type("string")
     * @Assert\Choice({"ASC", "DESC", "asc", "desc"})
     * @Assert\NotBlank()
     */
    private $order;

    public function __construct(?string $field = null, ?string $order = null)
    {
        $this->field = $field ?? '';
        $this->order = $order ?? 'ASC';
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOrder(): string
    {
        return $this->order;
    }
}