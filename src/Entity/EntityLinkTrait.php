<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

trait EntityLinkTrait
{
    /**
     * Сущності котрі прив'язани до данної сущності
     *
     * @param string $entityType
     * @return ArrayCollection|array
     */
    public function getLinkedEntities(string $entityType = null)
    {
        return $this->em->getRepository(self::class)->getLinkedEntities($this->getId(), $entityType);
    }

    /**
     * Сущності до яких прив'язана дана сущність
     *
     * @param string $entityType
     * @param int|null $limit
     * @return ArrayCollection|array
     */
    public function getLinkingEntities(string $entityType, int $limit = null)
    {
        return $this->em->getRepository(self::class)->getLinkingEntities($this->getId(), $entityType, $limit);
    }
}
