<?php

namespace App\Entity\Repository;

use App\DBAL\PublishedStatusEnum;
use App\Entity\Tag;
use App\Entity\TagTranslation;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TagRepository extends BaseRepository
{
    const NOT_DELETED = 't.deleted = 0';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getAll(bool $onlyActive = true, bool $returnBuilder = false)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where(self::NOT_DELETED)
            ->andWhere($this->methodMarker(__METHOD__))
            ->orderBy('t.id', 'DESC');

        if ($onlyActive) {
            $queryBuilder
                ->andWhere('t.status = :active')
                ->setParameter('active', PublishedStatusEnum::VALUE_PUBLISHED);
        }

        if ($returnBuilder) {
            return $queryBuilder;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $keyWord
     * @return Tag[]
     */
    public function findTagByKeyword(string $keyWord): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin(TagTranslation::class, 'tt', 'WITH', 'tt.translatable = t.id')
            ->where(self::NOT_DELETED)
            ->andWhere('tt.name LIKE :keyWord')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('t.status = :active')
            ->setParameter('active', PublishedStatusEnum::VALUE_PUBLISHED)
            ->setParameter('keyWord', '%' . $keyWord . '%')
            ->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return Tag
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findById(int $id): Tag
    {
        return $this->createQueryBuilder('t')
            ->where(self::NOT_DELETED)
            ->andWhere('t.id = :id')
            ->andWhere($this->methodMarker(__METHOD__))
            ->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }
}
