<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use App\DBAL\PublishedStatusEnum;
use App\DBAL\TableLogEnum;
use App\Entity\TableLog;
use App\Entity\Vacancy;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VacancyRepository extends BaseRepository
{
    const NOT_DELETED = 'v.deleted = 0';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vacancy::class);
    }

    /**
     * @param bool $returnBuilder
     * @param array $filters
     * @return QueryBuilder|mixed
     * @throws \Exception
     */
    public function getAll(bool $returnBuilder = false, array $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->where(self::NOT_DELETED)
            ->leftJoin('v.translations', 'vt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->orderBy('v.id', 'DESC');

        if (!empty($filters['name'])) {
            $queryBuilder->andWhere('vt.name LIKE :name');
            $queryBuilder->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['locale'])) {
            $queryBuilder->andWhere('vt.locale = :language');
            $queryBuilder->setParameter('locale', $filters['locale']);
        }

        if (!empty($filters['status']) && in_array($filters['status'], PublishedStatusEnum::getValues())) {
            $queryBuilder->andWhere('v.status = :status');
            $queryBuilder->setParameter('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $dates = explode(' - ', $filters['date']);
            list($dayStart, $monthStart, $yearStart) = explode('-', $dates[0]);
            list($dayEnd, $monthEnd, $yearEnd) = explode('-', $dates[1]);

            $dateStart = new \DateTime();
            $dateStart->setDate((int)$yearStart, (int)$monthStart, (int)$dayStart);
            $dateEnd = new \DateTime();
            $dateEnd->setDate((int)$yearEnd, (int)$monthEnd, (int)$dayEnd);

            $queryBuilder->leftJoin(TableLog::class, 'tl', 'WITH',
                'v.id = tl.objectId AND tl.tableName = :tableName')
                ->andWhere('tl.createdDate BETWEEN :dateStart AND :dateEnd')
                ->setParameter('dateStart', $dateStart->format('Y-m-d'))
                ->setParameter('tableName', TableLogEnum::Vacancy)
                ->setParameter('dateEnd', new \DateTime($dateEnd->format('Y-m-d') . '+ 1 day'));
        }

        if ($returnBuilder) {
            return $queryBuilder;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $locale
     * @param array $exclude
     * @param int|null $limit
     * @param int|null $offset
     * @return Vacancy[]
     * @throws \Exception
     *
     */
    public function getVacancies(string $locale, array $exclude = [], ?int $limit = null, ?int $offset = null): array
    {
        $queryBuilder = $this->getFrontQueryBuilder()
            ->leftJoin('v.translations', 'vt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('vt.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('v.id', 'DESC');

        if ($exclude) {
            $queryBuilder->andWhere('v.id NOT IN (:exclude)')
                ->setParameter('exclude', $exclude);
        }

        $this->addLimitOffset($queryBuilder, $limit, $offset);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $slug
     * @param string $locale
     * @param bool $isAdmin
     * @return Vacancy|null
     * @throws \Exception
     */
    public function getVacancyBySlug(string $slug, string $locale, bool $isAdmin = false): ?Vacancy
    {
        $queryBuilder = $this->getFrontQueryBuilder($isAdmin)
            ->leftJoin('v.translations', 'vt')
            ->andWhere($this->methodMarker(__METHOD__))
            ->andWhere('vt.slug = :slug')
            ->andWhere('vt.locale = :locale')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param bool $isAdmin
     *
     * @return QueryBuilder
     * @throws \Exception
     */
    protected function getFrontQueryBuilder(bool $isAdmin = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->where(self::NOT_DELETED);

        if (!$isAdmin) {
            $queryBuilder
                ->andWhere('v.status = :status')
                ->setParameter('status', PublishedStatusEnum::VALUE_PUBLISHED);
        }

        return $queryBuilder;
    }
}
