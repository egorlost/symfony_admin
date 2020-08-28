<?php

namespace App\Entity\Repository;

use App\Entity\TableLog;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TableLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableLog[]    findAll()
 * @method TableLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableLogRepository extends BaseRepository
{
    const NOT_DELETED = 'tl.deleted = 0';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TableLog::class);
    }

    /**
     * @param string $entityClass
     * @param int $objectId
     * @return null|object
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLog(string $entityClass, int $objectId): ?object
    {
        return $this->createQueryBuilder('tl')
            ->where(self::NOT_DELETED)
            ->andWhere('tl.tableName = :entityClass')
            ->andWhere('tl.objectId = :objectId')
            ->setParameter('entityClass', $entityClass)
            ->setParameter('objectId', $objectId)
            ->andWhere($this->methodMarker(__METHOD__))
            ->getQuery()->getOneOrNullResult();
    }
}
