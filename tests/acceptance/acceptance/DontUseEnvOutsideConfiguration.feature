@better-laravel
Feature: Env function was used outside the config files
  DontUseEnvOutsideConfiguration
  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
        <psalm errorLevel="1">
          <projectFiles>
            <directory name="../"/>
            <ignoreFiles>
              <directory name="../../vendor"/>
              <directory name="../acceptance"/>
            </ignoreFiles>
          </projectFiles>
          <plugins>
            <pluginClass class="Kafkiansky\BetterLaravel\Plugin"/>
          </plugins>
        </psalm>
      """
  Scenario: Asserting psalm recognizes that env function was used outside the configuration file
    Given I have the following code
      """
      <?php
      namespace Kafkiansky\BetterLaravel\Tests\_run;

      final class SomeController
      {
          public function index(): void
          {
             echo env('APP_ENV');
          }
      }
      """
    When I run Psalm
    Then I see these errors
      | Type                          | Message                                         |
      | EnvCanBeUsedJustInConfigFiles | Dont use the env function outside the configuration files, because it always returns null when caching configs. |

  Scenario: Asserting that env function can be used in config files without any issues
    Given I have the following code
      """
      <?php
      namespace Kafkiansky\BetterLaravel\Tests\_run;

      final class SomeController
      {
          public function index(): void
          {
             print_r(config('app.version'));
          }
      }
      """
    When I run Psalm
    Then I see no errors