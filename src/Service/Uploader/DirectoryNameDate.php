<?php declare(strict_types=1);

namespace App\Service\Uploader;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class DirectoryNameDate implements DirectoryNamerInterface
{
    public function directoryName($object, PropertyMapping $mapping): string
    {
        return date('Y/m');
    }
}
