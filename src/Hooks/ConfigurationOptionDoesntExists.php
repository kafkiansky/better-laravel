<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel\Hooks;

use Psalm\CodeLocation;
use Psalm\Issue\CodeIssue;

final class ConfigurationOptionDoesntExists extends CodeIssue
{
    public function __construct(string $optionName, CodeLocation $codeLocation)
    {
        parent::__construct(sprintf('Make sure that option "%s" exists.', $optionName), $codeLocation);
    }
}
