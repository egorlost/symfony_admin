<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

trait BaseFieldsTrait
{
    use EntityMarkerTrait;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    protected $deleted;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TableLog
     */
    protected $tableLog;

    public function __construct()
    {
        $this->deleted = false;
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @ORM\PostLoad
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->em = $args->getEntityManager();
    }

    /**
     * @param $deleted
     * @return $this
     */
    public function setDeleted($deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return \DateTimeInterface|null
     * @throws NonUniqueResultException
     */
    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->getTableLog()->getCreatedDate();
    }

    /**
     * @return \DateTimeInterface|null
     * @throws NonUniqueResultException
     */
    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->getTableLog()->getUpdatedDate();
    }

    /**
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function getCreatedUserId(): ?User
    {
        return $this->getTableLog()->getCreatedUserId();
    }

    /**
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function getLastModUserId(): ?User
    {
        return $this->getTableLog()->getLastModUserId();
    }

    /**
     * @return TableLog
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    protected function getTableLog(): TableLog
    {
        if ($this->tableLog !== null) {
            return $this->tableLog;
        }

        if ($this->em === null) {
            throw new \Exception('You need to get object from db');
        }

        return $this->tableLog = $this->em->getRepository(TableLog::class)
            ->getLog($this->classShortName(self::class), $this->getId());
    }

    /**
     * It returns class short name
     *
     * @param string $classFullName
     * @return string
     */
    protected function classShortName(string $classFullName): string
    {
        $pathParts = explode('\\', $classFullName);
        return array_pop($pathParts);
    }
}
