<?php

declare(strict_types=1);

namespace Aivchen\PsalmForbiddenPlugin;

use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeAnalysisEvent;

final class ForbidExtendHandler implements AfterClassLikeAnalysisInterface
{
    private static array $forbiddenItems = [];


    public static function setForbiddenItems(array $items): void
    {
        self::$forbiddenItems = $items;
    }

    public static function afterStatementAnalysis(AfterClassLikeAnalysisEvent $event)
    {
        $stmt = $event->getStmt();

        if (!isset($stmt->extends)) {
            return;
        }
        $name = (string)$stmt->extends;

        if (in_array($name, self::$forbiddenItems, true)) {
            IssueBuffer::accepts(
                new ForbiddenExtending(
                    "{$event->getStmt()->name} extends forbidden $name",
                    new CodeLocation($event->getStatementsSource(), $event->getStmt(), single_line: true)
                )
            );
        }
    }
}
