<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

/**
 * @psalm-suppress MissingFile
 */
final class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        require_once __DIR__.'/Hooks/DontExtendApplicationEventServiceProvider.php';
        require_once __DIR__.'/Hooks/DontUseEnvOutsideConfiguration.php';
        require_once __DIR__.'/Hooks/ConfigurationOptionMustExists.php';

        $registration->registerHooksFromClass(Hooks\DontExtendApplicationEventServiceProvider::class);
        $registration->registerHooksFromClass(Hooks\DontUseEnvOutsideConfiguration::class);
        $registration->registerHooksFromClass(Hooks\ConfigurationOptionMustExists::class);
    }
}
