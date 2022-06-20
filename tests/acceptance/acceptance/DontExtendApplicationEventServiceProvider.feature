@better-laravel
  Feature: Application EventServiceProvider extended
    DontExtendApplicationEventServiceProvider
  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
        <psalm errorLevel="1">
          <projectFiles>
            <directory name="."/>
            <ignoreFiles> <directory name="../../vendor"/> </ignoreFiles>
          </projectFiles>
          <plugins>
            <pluginClass class="Kafkiansky\BetterLaravel\Plugin"/>
          </plugins>
        </psalm>
      """
    Scenario: Asserting psalm recognizes that application event service provider was extended
      Given I have the following code
      """
      <?php
      namespace Kafkiansky\BetterLaravel\Tests\_run;

      use Kafkiansky\BetterLaravel\Tests\stubs\ApplicationEventServiceProvider;

      final class SomeApplicationServiceProvider extends ApplicationEventServiceProvider
      {
      }
      """
      When I run Psalm
      Then I see these errors
        | Type                                 | Message                                         |
        | VendorEventServiceProviderMustBeUsed | If you create an EventServiceProvider, you must inherit from the underlying service provider "Illuminate\Foundation\Support\Providers\EventServiceProvider", not from "Kafkiansky\BetterLaravel\Tests\stubs\ApplicationEventServiceProvider", to avoid duplicate listeners. |

    Scenario: Asserting that vendor EventServiceProvider can be extended without any issues
      Given I have the following code
      """
      <?php
      namespace Kafkiansky\BetterLaravel\Tests\_run;

      use Illuminate\Foundation\Support\Providers\EventServiceProvider;

      final class SomeApplicationServiceProvider extends EventServiceProvider
      {
      }
      """
      When I run Psalm
      Then I see no errors