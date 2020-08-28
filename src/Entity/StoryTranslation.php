<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="story_translation")
 * @ORM\Entity
 *
 * @UniqueEntity("slug")
 */
class StoryTranslation
{
    use Translation;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле name не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @Assert\NotBlank(message = "Поле name не може бути порожнім")
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Поле slug не може бути порожнім"
     * )
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле slug не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string|null
     *
     * @Assert\Length(
     *     max = 65535,
     *     maxMessage = "Поле annotation не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="annotation", type="text", length=65535, nullable=true)
     */
    private $annotation;

    /**
     * @var string|null
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле seo_title не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @Assert\NotBlank(
     *     message="Поле Seo title не може бути порожнім",
     *     groups={"seo"}
     * )
     *
     * @ORM\Column(name="seo_title", type="string", length=255, nullable=true)
     */
    private $seoTitle;

    /**
     * @var string|null
     *
     * @Assert\Length(
     *     max = 1024,
     *     maxMessage = "Поле seo_description не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @Assert\NotBlank(
     *     message="Поле Seo description не може бути порожнім",
     *     groups={"seo"}
     * )
     *
     * @ORM\Column(name="seo_description", type="string", length=1024, nullable=true)
     */
    private $seoDescription;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="StoryBlockText", cascade={"persist"}, mappedBy="storyTranslation")
     *
     * @Assert\Valid()
     *
     */
    protected $storyBlockText;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="StoryBlockImage", cascade={"persist"}, mappedBy="storyTranslation")
     *
     * @Assert\Valid()
     *
     */
    protected $storyBlockImage;

    public function __construct()
    {
        $this->storyBlockText = new ArrayCollection();
        $this->storyBlockImage = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAnnotation(): ?string
    {
        return $this->annotation;
    }

    public function setAnnotation(?string $annotation): self
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * @return Collection|StoryBlockText[]
     */
    public function getStoryBlockText(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('deleted', false));

        return $this->storyBlockText->matching($criteria);
    }

    public function addStoryBlockText(StoryBlockText $storyBlockText): self
    {
        if (!$this->storyBlockText->contains($storyBlockText)) {
            $this->storyBlockText[] = $storyBlockText;
            $storyBlockText->setStoryTranslation($this);
        }

        return $this;
    }

    public function removeStoryBlockText(StoryBlockText $storyBlockText): self
    {
        if ($this->storyBlockText->contains($storyBlockText)) {
            $this->storyBlockText->removeElement($storyBlockText);
            // set the owning side to null (unless already changed)
            if ($storyBlockText->getStoryTranslation() === $this) {
                $storyBlockText->setDeleted(true);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StoryBlockImage[]
     */
    public function getStoryBlockImage(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('deleted', false));

        return $this->storyBlockImage->matching($criteria);
    }

    public function addStoryBlockImage(StoryBlockImage $storyBlockImage): self
    {
        if (!$this->storyBlockImage->contains($storyBlockImage)) {
            $this->storyBlockImage[] = $storyBlockImage;
            $storyBlockImage->setStoryTranslation($this);
        }

        return $this;
    }

    public function removeStoryBlockImage(StoryBlockImage $storyBlockImage): self
    {
        if ($this->storyBlockImage->contains($storyBlockImage)) {
            $this->storyBlockImage->removeElement($storyBlockImage);
            // set the owning side to null (unless already changed)
            if ($storyBlockImage->getStoryTranslation() === $this) {
                $storyBlockImage->setDeleted(true);
            }
        }

        return $this;
    }

}
