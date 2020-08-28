<?php
declare(strict_types=1);

namespace App\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * It returns method marker for SQL request
     *
     * @param string $method result of const __METHOD__
     * @return string
     */
    protected function methodMarker(string $method): string
    {
        return "'" . substr(strrchr($method, '\\'), 1) . "' != '1'";
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param int|null $limit
     * @param int|null $offset
     */
    protected function addLimitOffset(QueryBuilder $queryBuilder, ?int $limit = null, ?int $offset = null): void
    {
        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }
        if (null !== $offset) {
            $queryBuilder->setFirstResult($offset);
        }
    }
}
