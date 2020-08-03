<?php
declare(strict_types=1);

namespace App\Lib\Infrastructure;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Exception;

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
}