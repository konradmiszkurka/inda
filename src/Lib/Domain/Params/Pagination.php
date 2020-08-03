<?php 
declare(strict_types=1);

namespace App\Lib\Domain\Params;

use App\Lib\Domain\Pagination\PaginationInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @noinspection PropertyInitializationFlawsInspection
 */
final class Pagination implements PaginationInterface
{
    /**
     * @var int
     * @Serializer\Type("integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $page = 1;
    /**
     * @var int
     * @Serializer\Type("integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $limit = 15;

    public function __construct(?int $page = null, ?int $limit = null)
    {
        $this->page = $page ?? 1;
        $this->limit = $limit ?? 15;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
