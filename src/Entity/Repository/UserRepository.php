<?php declare(strict_types=1);

namespace App\Entity\Repository;

use App\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $name
     * @param $typeUser
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findUser($name)
    {
        $result = $this->createQueryBuilder('u')
            ->leftJoin('App:TableLog', 'tl', 'WITH',
                'u.id = tl.objectId AND tl.tableName = :tableName')
            ->where('u.deleted = :deleted')
            ->setParameter('tableName', 'User')
            ->setParameter('deleted', false);

        if ($name != '') {
            $result = $result->andWhere('u.username LIKE :word')
                ->orWhere('u.email LIKE :word')
                ->orWhere('u.roles LIKE :word')
                ->setParameter('word', '%' . $name . '%');
        }

        $result = $result->orderBy('tl.createdDate', 'desc');
        $result->getQuery();

        return $result;
    }

    /**
     * @param int $id
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserById(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->andWhere('u.deleted = :deleted')
            ->andWhere($this->methodMarker(__METHOD__))
            ->setParameter('id', $id)
            ->setParameter('deleted', false)
            ->getQuery()->getOneOrNullResult();
    }
}
