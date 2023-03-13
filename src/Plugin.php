<?php

namespace Aivchen\PsalmForbiddenPlugin;

use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeAnalysisEvent;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

/**
 * @psalm-api
 */
class Plugin implements PluginEntryPointInterface, AfterClassLikeAnalysisInterface
{
    private static array $forbiddenExtend = [];

    /** @return void */
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        $psalm->registerHooksFromClass(self::class);

        if ($config === null) {
            return;
        }

        self::$forbiddenExtend = array_map(static fn($e) => (string)$e, (array)$config->extend);
    }

    public static function afterStatementAnalysis(AfterClassLikeAnalysisEvent $event): void
    {
        $stmt = $event->getStmt();

        if (!isset($stmt->extends)) {
            return;
        }
        $name = (string)$stmt->extends;

        if (in_array($name, self::$forbiddenExtend, true)) {
            IssueBuffer::accepts(
                new ForbiddenClassExtending(
                    "Class {$event->getStmt()->name} extends forbidden $name",
                    new CodeLocation($event->getStatementsSource(), $event->getStmt(), single_line: true)
                )
            );
        }
    }
}
