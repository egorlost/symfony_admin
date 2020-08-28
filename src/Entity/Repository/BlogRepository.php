<?php


namespace App\Entity\Repository;


use App\DBAL\PublishedStatusEnum;
use App\Entity\Blog;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogRepository extends BaseRepository
{
    use RepositoryLinkEntityTrait;

    const NOT_DELETED = 'b.deleted = 0';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @param array $filter
     * @param bool $returnBuilder
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    public function getAll(array $filter = [], bool $returnBuilder = false)
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->where(self::NOT_DELETED)
            ->andWhere($this->methodMarker(__METHOD__))
            ->orderBy('b.id', 'DESC');
        if ($returnBuilder) {
            return $queryBuilder;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $slug
     * @param string $locale
     * @param bool $isAdmin
     * @return Blog|null
     * @throws \Exception
     */
    public function getBlogBySlug(string $slug, string $locale, bool $isAdmin = false): ?Blog
    {
        return $this->getQueryBuilder($isAdmin)
            ->leftJoin('b.translations', 'bt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('bt.slug = :slug')
            ->andWhere('bt.locale = :locale')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $locale
     * @param array $exclude
     * @param int|null $limit
     * @param int|null $offset
     * @return Blog[]
     * @throws \Exception
     *
     */
    public function getBlogs(string $locale, array $exclude = [], ?int $limit = null, ?int $offset = null): array
    {
        $queryBuilder = $this->getQueryBuilder()
            ->leftJoin('b.translations', 'bt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('bt.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('b.id', 'DESC');

        if ($exclude) {
            $queryBuilder->andWhere('b.id NOT IN (:exclude)')
                ->setParameter('exclude', $exclude);
        }

        $this->addLimitOffset($queryBuilder, $limit, $offset);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCount()
    {
        $queryBuilder = $this->getQueryBuilder()
            ->andWhere($this->methodMarker(__METHOD__))
            ->select('count(b.id)');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param bool $isAdmin
     *
     * @return QueryBuilder
     * @throws \Exception
     */
    protected function getQueryBuilder(bool $isAdmin = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->where(self::NOT_DELETED);

        if (!$isAdmin) {
            $queryBuilder
                ->andWhere('b.publishDate <= :now')
                ->andWhere('b.status = :status')
                ->setParameter('status', PublishedStatusEnum::VALUE_PUBLISHED)
                ->setParameter('now', new \DateTime());
        }

        return $queryBuilder;
    }
}