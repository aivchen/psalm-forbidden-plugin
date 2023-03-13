Feature: basics
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
            <extend>BadClass</extend>
          </pluginClass>
        </plugins>
      </psalm>
      """

  Scenario: run with errors
    Given I have the following code
      """
      <?php
      class BadClass {}
      class A extends BadClass {}
      """
    When I run Psalm
    Then I see these errors
      | Type                  | Message                                                                             |
      | ForbiddenClassExtending | A extends forbidden BadClass |
    And I see no other errors

