Feature: Homepage
  In order to see the homepage
  As a website guest
  I need to be able to see and interact with the homepage

  Background:
    @javascript
    Given I am on the homepage

  Scenario: Can I see and use the reisplanner
    Then I should see "Dienstregeling"
    Then I take a screenshot
