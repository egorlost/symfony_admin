<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="story_block_text",
 *     indexes={
 *     @ORM\Index(name="story_translation_id", columns={"story_translation_id"})
 * })
 * @ORM\Entity
 */
class StoryBlockText
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @Assert\NotBlank(message = "Поле не може бути порожнім")
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @Assert\Length(
     *     max = 65535,
     *     maxMessage = "Поле не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @Assert\NotBlank(message = "Поле не може бути порожнім")
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var StoryTranslation
     *
     * @ORM\ManyToOne(targetEntity="StoryTranslation", inversedBy="storyBlockText")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="story_translation_id", referencedColumnName="id")
     * })
     */
    private $storyTranslation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    protected $deleted = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStoryTranslation(): ?StoryTranslation
    {
        return $this->storyTranslation;
    }

    public function setStoryTranslation(?StoryTranslation $storyTranslation): self
    {
        $this->storyTranslation = $storyTranslation;

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
}
