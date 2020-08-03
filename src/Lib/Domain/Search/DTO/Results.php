<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use App\Lib\Domain\Collection\AbstractCollection;
use JMS\Serializer\Annotation as Serializer;

final class Results
{
    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $searches;
    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $results;

    public function getSearches(): array
    {
        return $this->searches;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
