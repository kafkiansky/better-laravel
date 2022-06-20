@better-laravel
Feature: Configuration options must exists
  ConfigurationOptionMustExists
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
  Scenario: Asserting psalm recognizes that configuration option is missing in configuration tree.
    Given I have the following code
      """
      <?php
      namespace Kafkiansky\BetterLaravel\Tests\_run;

      final class SomeController
      {
         public function index(): void
         {
             print_r(config('app.nam'));
         }
      }
      """
    When I run Psalm
    Then I see these errors
      | Type                            | Message                                 |
      | ConfigurationOptionDoesntExists | Make sure that option "app.nam" exists. |

  Scenario: Asserting that right config can be used without psalm issues.
    Given I have the following code
      """
      <?php
      namespace Kafkiansky\BetterLaravel\Tests\_run;

      final class SomeController
      {
         public function index(): void
         {
             print_r(config('app.name'));
         }
      }
      """
    When I run Psalm
    Then I see no errors
