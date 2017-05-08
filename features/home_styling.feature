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
    Then I click on the class ".navigation__menu__text"
    Then I wait for 5 seconds
    Then I should see "Klantenservice"

  @javascript
  Scenario: Can I expand the bus lines
    Then I click on the class ".expand"
    Then I wait for 2 seconds
    Then I should see "713"

 @javascript
  Scenario: Can I use the GPS functionality
    Then I click on the class ".js-geolocation-toggle"
    Then I wait for 5 seconds
    Given the following lines exist:
    Then I test if line 8 of type "tram" is near me

   @javascript
   Scenario: I add a line to my favourites
     Given the following lines exist:
       | type   | lines |
       | Bus    | 37    |
     Then I add the line to my favourites
