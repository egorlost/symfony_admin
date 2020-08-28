<?php declare(strict_types=1);

namespace App\Entity\Repository;

use App\DBAL\PublishedStatusEnum;
use App\Entity\Tag;
use App\Entity\TagInEntity;
use Doctrine\Common\Collections\ArrayCollection;

trait RepositoryLinkEntityTrait
{
    /**
     * Сущності котрі прив'язани до данної сущності
     *
     * @param int $id
     * @param string $entityType
     * @return ArrayCollection|array
     *
     * @throws
     */
    public function getLinkedEntities(int $id, string $entityType = null)
    {
        $reflect = new \ReflectionClass($this->getClassName());

        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin(TagInEntity::class, 'tie', 'WITH',
                'e.id = tie.entityId AND tie.entityType = :entityType AND tie.deleted = 0')
            ->where('e.id = :id')
            ->setParameter('entityType', $reflect->getShortName())
            ->setParameter('id', $id);

        if ($entityType) {
            $queryBuilder
                ->select('el')
                ->innerJoin(Tag::class, 't', 'WITH',
                    'tie.tag = t.id AND t.type = :type AND t.deleted = 0')
                ->innerJoin('App:' . $entityType, 'el', 'WITH',
                    't.entityId = el.id AND el.deleted = 0 AND el.status = :status')
                ->setParameter('status', PublishedStatusEnum::VALUE_PUBLISHED)
                ->setParameter('type', $entityType);
        } else {
            $queryBuilder
                ->select('t')
                ->innerJoin(Tag::class, 't', 'WITH',
                    'tie.tag = t.id AND t.status = :status AND t.deleted = 0')
                ->setParameter('status', PublishedStatusEnum::VALUE_PUBLISHED);
        }

        $result = $queryBuilder->getQuery()->getResult();

        if (!$entityType) {
            $newResult = new ArrayCollection();

            foreach ($result as $item) {
                /**
                 * @var Tag $item
                 */
                $newResult->add($item->getEntity());
            }

            return $newResult;
        }

        return $result;
    }

    /**
     * Сущності до яких прив'язана дана сущність
     *
     * @param int $id
     * @param string $entityType
     * @param int|null $limit
     *
     * @return ArrayCollection
     *
     * @throws
     *
     */
    public function getLinkingEntities(int $id, string $entityType, int $limit = null): ArrayCollection
    {
        return $this->createQueryBuilder('e')
            ->select('el')
            ->leftJoin(TagInEntity::class, 'tie', 'WITH',
                'e.tag = tie.tag AND tie.entityType = :entityType AND tie.deleted = 0')
            ->innerJoin('App:' . $entityType, 'el', 'WITH',
                'tie.entityId = el.id AND el.deleted = 0 AND el.status = :status')
            ->setParameter('entityType', $entityType)
            ->setParameter('id', $id)
            ->setParameter('status', PublishedStatusEnum::VALUE_PUBLISHED)
            ->where('e.id = :id')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }
}
