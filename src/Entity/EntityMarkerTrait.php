<?php

namespace App\Entity;

trait EntityMarkerTrait
{
    /**
     * It marks entity as updated
     */
    protected function markEntityUpdated(): void
    {
        if (is_bool($this->deleted)) {
            $this->deleted = (int)$this->deleted;
        } else {
            $this->deleted = (bool)$this->deleted;
        }
    }
}
