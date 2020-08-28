<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TagTranslation
 *
 * @ORM\Table(name="tag_translation")
 * @ORM\Entity
 */
class TagTranslation
{
    use Translation;

    /**
     * @var string
     *
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Поле не може бути довшим ніж {{ limit }} символів!"
     * )
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
