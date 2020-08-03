<?php
declare(strict_types=1);

namespace App\Lib\Domain\Params;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final class Params
{
    /**
     * @var Pagination
     * @Serializer\Type("App\Lib\Domain\Params\Pagination")
     * @Assert\Valid()
     */
    private $pagination;
    /**
     * @var Filters
     * @Serializer\Type("App\Lib\Domain\Params\Filters")
     * @Assert\Valid()
     */
    private $filters;
    /**
     * @var Sorting|null
     * @Serializer\Type("App\Lib\Domain\Params\Sorting")
     * @Assert\Valid()
     */
    private $sorting;

    public function __construct(Pagination $pagination, Filters $filters, Sorting $sorting)
    {
        $this->pagination = $pagination;
        $this->filters = $filters;
        $this->sorting = $sorting;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination = $this->pagination ?? new Pagination();
    }

    public function getFilters(): Filters
    {
        return $this->filters = $this->filters ?? new Filters();
    }

    public function getSorting(): ?Sorting
    {
        return $this->sorting;
    }
}
