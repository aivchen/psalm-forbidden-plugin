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
        $storage = $event->getClasslikeStorage();
        $parent = $storage->parent_class;

        if (!$parent || !$storage->location) {
            return;
        }

        if (in_array($parent, self::$forbiddenItems, true)) {
            IssueBuffer::maybeAdd(
                new ForbiddenExtending(
                    "{$event->getStmt()->name} extends forbidden $parent",
                    $storage->location,
                    $storage->name
                ),
                $event->getStatementsSource()->getSuppressedIssues()
            );
        }
    }
}
