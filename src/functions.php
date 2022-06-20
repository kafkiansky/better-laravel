<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel;

/**
 * @param non-empty-string $parent
 *
 * @return class-string[]
 */
function classParents(string $parent): array
{
    $parents = [];
    do {
        $parents[] = $parent;
    } while (false !== ($parent = get_parent_class($parent)));

    /** @var class-string[] */
    return $parents;
}
