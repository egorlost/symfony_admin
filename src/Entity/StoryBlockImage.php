<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @ORM\Table(name="story_block_image",
 *     indexes={
 *     @ORM\Index(name="story_translation_id", columns={"story_translation_id"})
 * })
 * @ORM\Entity
 * @Vich\Uploadable
 */
class StoryBlockImage
{
    use EntityMarkerTrait;

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
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\Image(
     *     maxSize = "10000k",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png", "image/svg" },
     *     mimeTypesMessage = "Будь ласка загрузіть валідний jpg/png/svg",
     * )
     * @Vich\UploadableField(mapping="story_image", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @var string
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @Assert\NotBlank(message = "Поле не може бути порожнім")
     *
     * @ORM\Column(name="image_alt", type="string", length=255, nullable=false, options={"comment"="alt for image (text)"})
     */
    private $imageAlt;

    /**
     * @var string
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
     * @ORM\ManyToOne(targetEntity="StoryTranslation", inversedBy="storyBlockImage")
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
     * @return StoryBlockImage
     */
    public function setImageFile(File $imageFile = null): StoryBlockImage
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->markEntityUpdated();
        }

        return $this;
    }

    public function getImageAlt(): ?string
    {
        return $this->imageAlt;
    }

    public function setImageAlt(?string $imageAlt): self
    {
        $this->imageAlt = $imageAlt;

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
