<?php declare(strict_types=1);

namespace App\Entity\Repository;

use App\DBAL\PublishedStatusEnum;
use App\Entity\Story;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StoryRepository extends BaseRepository
{
    use RepositoryLinkEntityTrait;

    const NOT_DELETED = 't.deleted = 0';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Story::class);
    }

    public function getAll(array $filter = [], bool $returnBuilder = false)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where(self::NOT_DELETED)
            ->andWhere($this->methodMarker(__METHOD__))
            ->orderBy('t.id', 'DESC');

        if ($returnBuilder) {
            return $queryBuilder;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $slug
     * @param string $locale
     * @param bool $isAdmin
     * @return Story|null
     * @throws \Exception
     */
    public function getStoryBySlug(string $slug, string $locale, bool $isAdmin = false): ?Story
    {
        return $this->getQueryBuilder($isAdmin)
            ->leftJoin('t.translations', 'tt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('tt.slug = :slug')
            ->andWhere('tt.locale = :locale')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $locale
     * @param array $exclude
     * @param boolean $atMainPage
     * @param int|null $limit
     * @param int|null $offset
     * @return Story[]
     * @throws \Exception
     *
     */
    public function getStories(string $locale, array $exclude = [], $atMainPage = false, ?int $limit = null, ?int $offset = null): array
    {
        $queryBuilder = $this->getQueryBuilder()
            ->leftJoin('t.translations', 'tt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('tt.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('t.id', 'DESC');

        if ($exclude) {
            $queryBuilder->andWhere('t.id NOT IN (:exclude)')
                ->setParameter('exclude', $exclude);
        }

        if ($atMainPage) {
            $queryBuilder->andWhere('t.atMainPage = 1');
        }

        $this->addLimitOffset($queryBuilder, $limit, $offset);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param bool $isAdmin
     *
     * @return QueryBuilder
     * @throws \Exception
     */
    protected function getQueryBuilder(bool $isAdmin = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where(self::NOT_DELETED);

        if (!$isAdmin) {
            $queryBuilder
                ->andWhere('t.status = :status')
                ->setParameter('status', PublishedStatusEnum::VALUE_PUBLISHED);
        }

        return $queryBuilder;
    }
}
