<?php declare(strict_types=1);

namespace App\Entity;

interface BaseFieldsI
{
    public function setDeleted($deleted);
    public function getDeleted();
}
