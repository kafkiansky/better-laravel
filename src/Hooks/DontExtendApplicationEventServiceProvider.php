<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel\Hooks;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeAnalysisEvent;
use function Kafkiansky\BetterLaravel\classParents;

final class DontExtendApplicationEventServiceProvider implements AfterClassLikeAnalysisInterface
{
    private const VENDOR_PROVIDER_NAME = 'Illuminate\Foundation\Support\Providers\EventServiceProvider';

    /**
     * {@inheritdoc}
     */
    public static function afterStatementAnalysis(AfterClassLikeAnalysisEvent $event): ?bool
    {
        $stmt = $event->getStmt();

        if ($stmt instanceof Class_) {
            if ($stmt->extends instanceof Name) {
                /** @var null|non-empty-string $extend */
                $extend = $stmt->extends->getAttribute('resolvedName');

                if (null === $extend) {
                    return null;
                }

                if (\in_array(self::VENDOR_PROVIDER_NAME, classParents($extend)) && self::VENDOR_PROVIDER_NAME !== $extend) {
                    IssueBuffer::accepts(
                        new VendorEventServiceProviderMustBeUsed(
                            new CodeLocation($event->getStatementsSource(), $event->getStmt()),
                            self::VENDOR_PROVIDER_NAME,
                            $extend,
                        ),
                        $event->getStatementsSource()->getSuppressedIssues(),
                    );
                }
            }
        }

        return null;
    }
}
