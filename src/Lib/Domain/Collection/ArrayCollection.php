<?php
declare(strict_types=1);

namespace App\Lib\Domain\Collection;

final class ArrayCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'array';
    }

    public function getAll(): array
    {
        foreach ($this->data as $key => $data) {
            $this->data[$key] = (new $this->dataMapping())
                ->setData($data);
        }

        return $this->data;
    }
}