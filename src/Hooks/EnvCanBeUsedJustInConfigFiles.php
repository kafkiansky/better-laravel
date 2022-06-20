<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel\Hooks;

use Psalm\CodeLocation;
use Psalm\Issue\CodeIssue;

final class EnvCanBeUsedJustInConfigFiles extends CodeIssue
{
    public function __construct(CodeLocation $codeLocation)
    {
        parent::__construct('Dont use the env function outside the configuration files, because it always returns null when caching configs.', $codeLocation);
    }
}
