Feature: Homepage
  In order to see the homepage
  As a website guest
  I need to be able to see and interact with the homepage

  Background:
    Given I am on the homepage

  @javascript
  Scenario: Can I see and use the Dienstregeling
    Then I should see "Dienstregeling"

  @javascript
  Scenario: Can I see the menu
    Then I should see "MENU"
    Then I click on the selector ".navigation__menu__text"
    Then I wait for 5 seconds
    Then I should see "Klantenservice"

  @javascript
  Scenario: Can I expand the bus lines
    Then I click on the selector ".expand"
    Then I wait for 2 seconds
    Then I should see "713"

  @javascript @test
  Scenario: I should be able to see and dismiss the cookie bar and not see it when I re-visit the site.
    Then I click on the selector ".cookie-bar__close"
    Then I reload the page
    Then I wait for 2 seconds
    Then I take a screenshot
    Then I should not see "RET.nl maakt gebruik van cookies,"
    