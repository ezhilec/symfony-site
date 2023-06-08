<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GlobalVariableExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GlobalVariableExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMenuPages', [GlobalVariableExtensionRuntime::class, 'getMenuPages']),
            new TwigFunction('getAdminPages', [GlobalVariableExtensionRuntime::class, 'getAdminPages']),
            new TwigFunction('getUploadsDir', [GlobalVariableExtensionRuntime::class, 'getUploadsDir']),
        ];
    }
}
