<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel\Hooks;

use Psalm\CodeLocation;
use Psalm\Issue\CodeIssue;

final class VendorEventServiceProviderMustBeUsed extends CodeIssue
{
    public function __construct(CodeLocation $codeLocation, string $shouldBeUsed, string $whatWasUsedInstead)
    {
        parent::__construct(
            vsprintf('If you create an EventServiceProvider, you must inherit from the underlying service provider "%s", not from "%s", to avoid duplicate listeners.', [
                $shouldBeUsed,
                $whatWasUsedInstead,
            ]),
            $codeLocation,
        );
    }
}
