<?php declare(strict_types=1);

namespace App\Service\Uploader;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\UniqidNamer;

class FileNameFullPath extends UniqidNamer
{
    /**
     * @var DirectoryNameDate
     */
    private $directoryName;

    public function __construct(DirectoryNameDate $directoryName)
    {
        $this->directoryName = $directoryName;
    }

    public function name($object, PropertyMapping $mapping): string
    {
        $basePath = $mapping->getUriPrefix();
        $subDir = $this->directoryName->directoryName($object, $mapping);
        $fileName = parent::name($object, $mapping);

        return $basePath.'/'.$subDir.'/'.$fileName;
    }
}
