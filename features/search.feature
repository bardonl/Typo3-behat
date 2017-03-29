Feature: Search
  In order to search
  As a website guest
  I need to be able to enter text in the searchbox and search

  @javascript
  Scenario: Searching for an item that does exist
    Given I am on the homepage
    When I fill in "Waar zoek je naar?" with "Tram 23"
    When I click on the selector ".search__icon"
    Then I wait for 3 seconds
    Then the url should match "zoekresultaten.html"
    Then I should see "Resultaten gevonden"

  @javascript
  Scenario: Searching for an item that does not exist
    Given I am on the homepage
    When I fill in "Waar zoek je naar?" with "hijmoetnuechtniksvidenanderswordikgek"
    When I click on the selector ".search__icon"
    Then I wait for 3 seconds
    Then the url should match "zoekresultaten.html"
    Then I should not see "Resultaten gevonden"