Feature: namespaced
  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
      <psalm totallyTyped="true">
        <projectFiles>
          <directory name="."/>
        </projectFiles>
        <plugins>
          <pluginClass class="Aivchen\PsalmForbiddenPlugin\Plugin">
            <extend>BadNamespace\BadClass</extend>
          </pluginClass>
        </plugins>
      </psalm>
      """

  Scenario: run with errors
    Given I have the following code
      """
      <?php
      namespace BadNamespace;

      class BadClass {}

      namespace MyNameSpace;

      class A extends \BadNamespace\BadClass {}
      """
    When I run Psalm
    Then I see these errors
      | Type                  | Message                                                                             |
      | ForbiddenClassExtending | A extends forbidden BadNamespace\BadClass |
    And I see no other errors
