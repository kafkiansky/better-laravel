<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel\Hooks;

use PhpParser\Node\Name;
use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterFunctionCallAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterFunctionCallAnalysisEvent;

final class DontUseEnvOutsideConfiguration implements AfterFunctionCallAnalysisInterface
{
    private const ENV_FUNCTION_NAME = 'env';
    private const CONFIG_DIR = 'config';

    public static function afterFunctionCallAnalysis(AfterFunctionCallAnalysisEvent $event): void
    {
        $functionName = $event->getExpr()->name;

        if ($functionName instanceof Name) {
            if (false === $functionName->hasAttribute('resolvedName') && self::ENV_FUNCTION_NAME === $functionName->toString()) {
                $path = $event->getStatementsSource()->getFileName();

                if (self::CONFIG_DIR !== strtolower(pathinfo($path)['dirname'] ?? '')) {
                    IssueBuffer::accepts(
                        new EnvCanBeUsedJustInConfigFiles(
                            new CodeLocation($event->getStatementsSource(), $event->getExpr()),
                        ),
                    );
                }
            }
        }
    }
}
