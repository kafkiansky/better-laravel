<?php

declare(strict_types=1);

namespace Kafkiansky\BetterLaravel\Hooks;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Psalm\CodeLocation;
use Psalm\Config;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterFunctionCallAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterFunctionCallAnalysisEvent;

final class ConfigurationOptionMustExists implements AfterFunctionCallAnalysisInterface
{
    private const CONFIG_FUNCTION_NAME = 'config';
    private const PROJECT_CONFIGURATION_PATH = 'config';

    /**
     * @psalm-suppress UnresolvableInclude
     */
    public static function afterFunctionCallAnalysis(AfterFunctionCallAnalysisEvent $event): void
    {
        $basePath = Config::getInstance()->base_dir;

        self::prepareApplication($basePath);

        /** @var array */
        static $configuration = [];

        if (0 === \count($configuration)) {
            $projectDirectories = Config::getInstance()->getProjectDirectories();

            if (\file_exists($configDir = $basePath.'/'.self::PROJECT_CONFIGURATION_PATH)) {
                $projectDirectories[] = $configDir;
            }

            foreach ($projectDirectories as $projectDirectory) {
                /** @var \SplFileInfo $file */
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($projectDirectory)) as $file) {
                    if ($file->isDir() || 'php' !== $file->getExtension() || false === $file->getRealPath()) {
                        continue;
                    }

                    if (self::PROJECT_CONFIGURATION_PATH === strtolower(pathinfo($file->getPath())['basename'] ?? '')) {
                        $basename = explode('.', $file->getBasename())[0] ?? null;

                        if (null !== $basename) {
                            /** @var array */
                            $config = require_once $file->getRealPath();
                            $configuration[$basename] = $config;
                        }
                    }
                }
            }
        }

        $functionName = $event->getExpr()->name;

        if ($functionName instanceof Name) {
            if (false === $functionName->hasAttribute('resolvedName') && self::CONFIG_FUNCTION_NAME === $functionName->toString()) {
                $args = $event->getExpr()->getArgs();

                if (0 < \count($args)) {
                    $value = $args[0]->value;

                    if ($value instanceof String_) {
                        if (false === Arr::has($configuration, $value->value)) {
                            IssueBuffer::accepts(
                                new ConfigurationOptionDoesntExists(
                                    $value->value,
                                    new CodeLocation($event->getStatementsSource(), $event->getExpr()),
                                ),
                                $event->getStatementsSource()->getSuppressedIssues(),
                            );
                        }
                    }
                }
            }
        }
    }

    private static function prepareApplication(string $basePath): void
    {
        $app = new Application($basePath);
        $app->bind('config', fn (): Repository => new Repository());

        Container::setInstance($app);
    }
}
