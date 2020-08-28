<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * BlogTranslation
 *
 * @ORM\Table(name="blog_translation")
 * @ORM\Entity
 *
 * @UniqueEntity("slug")
 */
class BlogTranslation
{
    use Translation;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Поле Назва не може бути порожнім"
     * )
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле name не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Поле Транслітерація не може бути порожнім"
     * )
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле Транслітерація не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Поле Анотація не може бути порожнім"
     * )
     *
     * @Assert\Length(
     *     max = 65535,
     *     maxMessage = "Поле Анотація не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="annotation", type="text", length=65535, nullable=false)
     */
    private $annotation;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Поле Текст не може бути порожнім"
     * )
     *
     * @Assert\Length(
     *     max = 65535,
     *     maxMessage = "Поле Текст не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

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

    public function setAnnotation(string $annotation): self
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
}
