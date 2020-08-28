<?php declare(strict_types=1);

namespace App\Entity\Repository;

use App\Entity\Tag;
use App\Entity\TagInEntity;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TagInEntityRepository extends BaseRepository
{
    const NOT_DELETED = 'tie.deleted = 0';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TagInEntity::class);
    }

    public function tinExists(Tag $tag, $entityId, $entityType)
    {
        $result = $this->createQueryBuilder('tie')
            ->where(self::NOT_DELETED)
            ->andWhere('tie.tag = :tag')
            ->andWhere('tie.entityId = :entityId')
            ->andWhere('tie.entityType = :entityType')
            ->andWhere($this->methodMarker(__METHOD__))
            ->setParameter('tag', $tag)
            ->setParameter('entityId', $entityId)
            ->setParameter('entityType', $entityType)
            ->getQuery()->getResult();

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getEntityTags($entityId, $entityType): array
    {
        return $this->createQueryBuilder('tie')
            ->select('t')
            ->leftJoin(Tag::class, 't', 'WITH', 'tie.tag = t.id')
            ->where(self::NOT_DELETED)
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('t.deleted = :deleted')
            ->andWhere('tie.entityId = :entityId')
            ->andWhere('tie.entityType = :entityType')
            ->setParameter('deleted', false)
            ->setParameter('entityId', $entityId)
            ->setParameter('entityType', $entityType)
            ->getQuery()->getResult();
    }
}
