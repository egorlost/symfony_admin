<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * TagInEntity
 *
 * @ORM\Table(name="tag_in_entity",
 *     indexes={
 *     @ORM\Index(name="tag_id", columns={"tag_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Entity\Repository\TagInEntityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TagInEntity
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_type", type="string", length=20, nullable=false)
     */
    private $entityType;

    /**
     * @var Tag
     *
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="tagInEntity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     * })
     */
    private $tag;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    protected $deleted = 0;

    public function __construct()
    {
        $this->deleted = 0;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return object|null
     */
    public function getEntity()
    {
        return $this->em->getRepository('App:' . $this->getEntityType())->find($this->getEntityId());
    }
}
