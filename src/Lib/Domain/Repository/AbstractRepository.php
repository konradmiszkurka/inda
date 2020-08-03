<?php
declare(strict_types=1);

namespace App\Lib\Domain\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use App\Lib\Domain\Collection\CollectionInterface;
use App\Lib\Domain\Collection\ObjectCollection;
use App\Lib\Domain\Criteria\CriteriaInterface;
use App\Lib\Domain\Pagination\PaginationInterface;
use App\Lib\Domain\Sorting\SortingInterface;

abstract class AbstractRepository
{
    /**
     * @var ObjectRepository|EntityRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        if ($this->getEntityFQN()) {
            $this->repository = $this->entityManager->getRepository($this->getEntityFQN());
        }
    }

    abstract protected function getEntityFQN(): ?string;

    protected function prepareQueryBuilderToFindAll(): QueryBuilder
    {
        return $this->repository->createQueryBuilder('e');
    }

    public function flush(): void
    {
        try {
            $this->entityManager->flush();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findAll(
        ?CriteriaInterface $criteria = null,
        ?PaginationInterface $pagination = null,
        ?SortingInterface $sorting = null,
        ?QueryBuilder $baseQueryBuilder = null,
        ?string $outputType = null
    ): CollectionInterface {
        if ($baseQueryBuilder === null) {
            $baseQueryBuilder = $this->prepareQueryBuilderToFindAll();
        }
        if ($outputType === null) {
            $outputType = ObjectCollection::class;
        }

        $qb = $baseQueryBuilder;
        if (null !== $criteria) {
            $this->insertCriteriaToQB($qb, $criteria);
        }
        if (null !== $pagination) {
            $this->insertPaginationToQB($qb, $pagination);
        }
        if (null !== $sorting) {
            $this->insertSortingToQB($qb, $sorting);
        }

        $query = $qb->getQuery();

        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        return new $outputType($query->getResult(), count($paginator));
    }

    protected function insertCriteriaToQB(QueryBuilder $qb, CriteriaInterface $criteria): QueryBuilder
    {
        $is = [];
        $prefixes = [];
        $in = [];
        $notIn = [];
        $notIs = [];
        foreach ($criteria->getFieldsCriteria() as $fieldName => $criterion) {
            foreach ($criterion as $crName => $values) {
                if ('prefix' === $crName) {
                    $prefixes[$fieldName] = $values;
                }
                if ('is' === $crName) {
                    $is[$fieldName] = $values;
                }
                if ('in' === $crName) {
                    $in[$fieldName] = $values;
                }
                if ('notIn' === $crName) {
                    $notIn[$fieldName] = $values;
                }
                if ('notIs' === $crName) {
                    $notIs[$fieldName] = $values;
                }
            }
        }

        $this->getAllowedFields();
        $this->clearFieldName();

        $parmIndex = 1;
        foreach ($in as $fieldName => $values) {
            $ordered = array_reduce($values, static function ($val, $elem) use (&$parmIndex) {
                if (!empty($elem)) {
                    $val[$parmIndex++] = $elem;
                }

                return $val;
            }, []);

            if (empty($ordered)) {
                continue;
            }

            $qb->andWhere(sprintf('%s IN (%s)', $this->getRealFieldName($fieldName),
                implode(' , ', array_map(static function ($elem) {
                    return '?' . $elem;
                }, array_keys($ordered)))));
            foreach ($ordered as $key => $item) {
                $qb->setParameter($key, $item);
            }
        }
        foreach ($notIn as $fieldName => $values) {
            $ordered = array_reduce($values, static function ($val, $elem) use (&$parmIndex) {
                $val[$parmIndex++] = $elem;

                return $val;
            }, []);

            $qb->andWhere(sprintf('%s NOT IN (%s)', $this->getRealFieldName($fieldName),
                implode(' , ', array_map(static function ($elem) {
                    return '?' . $elem;
                }, array_keys($ordered)))));
            foreach ($ordered as $key => $item) {
                $qb->setParameter($key, $item);
            }
        }
        foreach ($prefixes as $fieldName => $values) {
            if (empty($values)) {
                continue;
            }

            $valueParam = $parmIndex++;
            $qb->andWhere(sprintf('%s LIKE ?%s', $this->getRealFieldName($fieldName), $valueParam))
                ->setParameter($valueParam, $values . '%');
        }
        foreach ($is as $fieldName => $values) {
            if (empty($values)) {
                continue;
            }

            $valueParam = $parmIndex++;
            $qb->andWhere(sprintf('%s = ?%s', $this->getRealFieldName($fieldName), $valueParam))
                ->setParameter($valueParam, $values);
        }
        foreach ($notIs as $fieldName => $values) {
            $valueParam = $parmIndex++;
            $qb->andWhere(sprintf('%s != ?%s', $this->getRealFieldName($fieldName), $valueParam))
                ->setParameter($valueParam, $values);
        }

        return $qb;
    }

    protected function insertPaginationToQB(QueryBuilder $qb, PaginationInterface $pagination): QueryBuilder
    {
        $qb->setFirstResult(($pagination->getPage() - 1) * $pagination->getLimit())
            ->setMaxResults($pagination->getLimit());

        return $qb;
    }

    protected function insertSortingToQB(QueryBuilder $qb, SortingInterface $sorting): QueryBuilder
    {
        $qb->addOrderBy("e.{$sorting->getField()}", $sorting->getOrder());

        return $qb;
    }

    public function getAllowedFields(): array
    {
        try {
            $reflection = new \ReflectionClass($this->getEntityFQN());

            return array_keys($reflection->getdefaultProperties());
        } catch (\ReflectionException $e) {
            return [];
        }
    }

    private function getRealFieldName(string $fieldName): string
    {
        if (strpos($fieldName, '|') !== false) {
            return str_replace('|', '.', $fieldName);
        }

        return 'e.' . $fieldName;
    }

    private function clearFieldName(?string $fieldName = null): string
    {
        if ($fieldName === null) {
            return '';
        }

        if (strpos($fieldName, '|') !== false) {
            $explode = explode('|', $fieldName);

            return $explode[1];
        }

        return $fieldName;
    }
}