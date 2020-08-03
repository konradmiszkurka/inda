<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search;

use App\Lib\Domain\Search\DTO\Element;
use App\Lib\Domain\Search\DTO\Results;
use JMS\Serializer\ArrayTransformerInterface;

class SearchRegistry
{
    private $services = [];

    /**
     * @var ArrayTransformerInterface
     */
    private $arrayTransformer;

    public function __construct(
        ArrayTransformerInterface $arrayTransformer
    ) {
        $this->arrayTransformer = $arrayTransformer;
    }

    public function add($service): void
    {
        $this->services[] = $service;
    }

    public function getAll(): array
    {
        return $this->services;
    }

    public function getToSearchResult(string $query, ?int $type = null): Results
    {
        $searches = [];
        $results = [];

        /** @var SearchInterfaces $service */
        foreach ($this->getAll() as $key => $service) {
            $elements = $service->search($query);
            $searches[$key+1] = $elements->getName();

            if (!empty($type) && $type !== null && $type !== 0 && $type-1 !== $key) {
                continue;
            }

            foreach ($elements->getAll() as $result) {
                $results[] = $result;
            }
        }

        usort($results, static function (Element $a, Element $b) {
            return ($a->getDate()->getDateTime() > $b->getDate()->getDateTime()) ? -1 : 1;
        });

        return $this->arrayTransformer->fromArray([
            'searches' => $searches,
            'results' => $results,
        ], Results::class);
    }
}