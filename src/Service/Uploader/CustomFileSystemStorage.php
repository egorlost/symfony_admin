<?php declare(strict_types=1);

namespace App\Service\Uploader;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\FileSystemStorage;

class CustomFileSystemStorage extends FileSystemStorage
{
    /**
     * {@inheritdoc}
     */
    protected function doResolvePath(PropertyMapping $mapping, ?string $dir, string $name, ?bool $relative = false): string
    {
        $path = preg_replace('/^[^\d]+/', '', $name);

        if ($relative) {
            return $path;
        }

        return $mapping->getUploadDestination() . DIRECTORY_SEPARATOR . $path;
    }

    protected function doRemove(PropertyMapping $mapping, ?string $dir, string $name): ?bool
    {
        $file = $this->doResolvePath($mapping, $dir, $name);

        if (is_dir($file)) {
            return false;
        }

        return file_exists($file) ? unlink($file) : false;
    }
}
