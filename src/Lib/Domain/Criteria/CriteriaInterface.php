<?php
declare(strict_types=1);

namespace App\Lib\Domain\Criteria;

interface CriteriaInterface
{
    public function getFieldsCriteria(): array;
}
