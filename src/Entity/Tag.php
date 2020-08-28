<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tag implements BaseFieldsI
{
    use BaseFieldsTrait;
    use Translatable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="published_status_enum", nullable=false, options={"default"="UNPUBLISHED"})
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="tag_type_enum", nullable=false)
     */
    private $type;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="TagInEntity", cascade={"persist"}, mappedBy="tag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="tag_id")
     * })
     */
    private $tagInEntity;

    public function __call($method, $arguments)
    {
        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->translate()->getName() ?? $this->id;
    }

    /**
     * @return object|null
     */
    public function getEntity()
    {
        return $this->em->getRepository('App:' . $this->getType())->find($this->getEntityId());
    }

    /**
     * @param string $entityType
     * @param int|null $limit
     * @return Collection|TagInEntity[]
     */
    public function getTagInEntity(string $entityType, int $limit = null): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("deleted", false))
            ->andWhere(Criteria::expr()->eq("entityType", $entityType))
            ->setMaxResults($limit);

        return $this->tagInEntity->matching($criteria);
    }
}
