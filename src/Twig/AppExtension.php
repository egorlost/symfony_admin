<?php
declare(strict_types=1);

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('set_version', [$this, 'setVersion']),
        ];
    }

    public function setVersion(string $asset): string
    {
        $assetVersion = $_SERVER['ASSET_VERSION'] ?? 1;

        $sign = '?';
        if (strpos($asset, '?') !== false) {
            $sign = '&';
        }

        return $asset . $sign . 'v=' . $assetVersion;
    }
}