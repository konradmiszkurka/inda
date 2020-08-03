<?php
declare(strict_types=1);

namespace App\Lib\Domain\Responder\DTO;

use App\Lib\Domain\Params\Pagination;
use JMS\Serializer\Annotation as Serializer;

class PaginationDTO
{
    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $page;
    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $limit;
    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $total;

    public function __construct(int $page, int $limit, int $totalElements)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $totalElements;
    }

    public static function createFromPagination(Pagination $pagination, int $totalElements): self
    {
        return new self($pagination->getPage(), $pagination->getLimit(), $totalElements);
    }
}
