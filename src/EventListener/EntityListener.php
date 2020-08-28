<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\BaseFieldsI;
use App\Entity\TableLog;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class EntityListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * EntityListener constructor.
     *
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * If we updating entity
     *
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->updateSystemEntityFields($args);
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->updateSystemEntityFields($args);
    }

    /**
     * It updates system entity fields
     *
     * @param LifecycleEventArgs $args
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    private function updateSystemEntityFields(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if ($entity instanceof TableLog) {
            return;
        }

        if ($entity instanceof BaseFieldsI) {
            $em = $args->getEntityManager();

            $entityClassName = $this->getObjectClassName($entity);
            $tableLog = $em->getRepository(TableLog::class)->getLog($entityClassName, $entity->getId());
            if ($tableLog === null) {
                $tableLog = new TableLog();
                $tableLog->setCreatedUserId($this->currentUser());
                $tableLog->setCreatedDate(new \DateTime());
                $tableLog->setObjectId($entity->getId());
                $tableLog->setTableName($entityClassName);
            }

            $tableLog->setLastModUserId($this->currentUser());
            $tableLog->setUpdatedDate(new \DateTime());

            $em->persist($tableLog);
            $em->flush();
        }
    }

    /**
     * @param BaseFieldsI $entity
     * @return string
     */
    private function getObjectClassName(BaseFieldsI $entity): string
    {
        $path = explode('\\', get_class($entity));

        return array_pop($path);
    }

    /**
     * It returns current user
     *
     * @return User|null
     */
    private function currentUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if ($token && $token->getUser() instanceof User) {
            return $token->getUser();
        }

        return null;
    }
}
