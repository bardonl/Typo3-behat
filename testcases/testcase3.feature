Feature: Testcase 1
  In order to see the homepage
  As a website guest
  I need to be able to use the homepage

  Background:
    Given I am on the homepage

  @javascript
  Scenario: Can I register for a new account
    Then I try to create a new account