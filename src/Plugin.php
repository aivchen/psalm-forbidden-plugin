<?php

namespace Aivchen\PsalmForbiddenPlugin;

use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

/**
 * @psalm-api
 */
class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        if ($config === null) {
            return;
        }

        $items = array_map(static fn($e) => (string)$e, (array)$config->extend);
        ForbidExtendHandler::setForbiddenItems($items);
        $psalm->registerHooksFromClass(ForbidExtendHandler::class);
    }
}
