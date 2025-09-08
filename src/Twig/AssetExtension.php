<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('vue_asset', [$this, 'getVueAsset']),
        ];
    }

    public function getVueAsset(string $type): ?string
    {
        $assetFile = __DIR__ . '/../../config/assets.php';
        
        if (!file_exists($assetFile)) {
            return null;
        }
        
        $assets = include $assetFile;
        
        return $assets[$type] ?? null;
    }
}
