<?php

namespace App\Entity;

use App\DBAL\PublishedStatusEnum;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use App\DBAL\TagTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Blog
 *
 * @method string getName()
 * @method string getSlug()
 * @method string getLocale()
 * @method string getAnnotation()
 * @method string getText()
 * @method string getSeoTitle()
 * @method string getSeoDescription()
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\BlogRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Blog implements BaseFieldsI
{
    use BaseFieldsTrait;
    use EntityLinkTrait;
    use Translatable;

    const OTHER_BLOGS_LIMIT = 3;

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
     * @var \DateTime
     *
     * @ORM\Column(name="publish_date", type="datetime", nullable=true)
     */
    private $publishDate;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\Image(
     *     maxSize = "10000k",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png", "image/svg" },
     *     mimeTypesMessage = "Будь ласка загрузіть валідний jpg/png/svg",
     * )
     * @Vich\UploadableField(mapping="blog_image", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="TagInEntity", cascade={"persist"}, mappedBy="entityId")
     */
    private $tagInEntity;

    public function __call($method, $arguments)
    {
        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(...$arguments), $method);
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

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(?\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * (If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.)
     *
     * @param File|UploadedFile $imageFile
     *
     * @return Blog
     */
    public function setImageFile(File $imageFile = null): self
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->markEntityUpdated();
        }

        return $this;
    }

    /**
     * @return Collection|TagInEntity[]
     */
    public function getTagInEntity(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("deleted", false))
            ->andWhere(Criteria::expr()->eq("entityType", TagTypeEnum::VALUE_BLOG));
        return $this->tagInEntity->matching($criteria);
    }

    public function addTagInEntity(TagInEntity $tagInEntity): self
    {
        if (!$this->tagInEntity->contains($tagInEntity)) {
            $this->tagInEntity[] = $tagInEntity;
            $tagInEntity->setEntityId($this->getId());
            $tagInEntity->setEntityType(TagTypeEnum::VALUE_BLOG);
        }

        return $this;
    }

    public function removeTagInEntity(TagInEntity $tagInEntity): self
    {
        if ($this->tagInEntity->contains($tagInEntity)) {
            $this->tagInEntity->removeElement($tagInEntity);
            // set the owning side to null (unless already changed)
            if ($tagInEntity->getEntityId() === $this->getId()) {
                $tagInEntity->setDeleted(true);
            }
        }

        return $this;
    }

    public function isEmptyRecord(): bool
    {
        return null === $this->getName() && $this->getDeleted();
    }
}
