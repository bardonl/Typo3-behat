Feature: Search
  In order to search
  As a website guest
  I need to be able to enter text in the searchbox and search

  Background:
    Given I am on the homepage

  Scenario: Searching for a line that does exist
    Then I search the RET site with "Tram 25"
    Then print current URL
    Then I should not see "We hebben geen resultaat kunnen vinden."
    Then I should see "tram 25"
    When I follow "Lees verder"
    Then the url should match "tram-25"

  Scenario: Searching for a line that does not exist
    Then I search the RET site with "Tram 9000"
    Then print current URL
    Then I should not see "Resultaten gevonden"
    Then I should see "We hebben geen resultaat kunnen vinden."

  Scenario: I'm a hacker
    Then I search the RET site with ""; huehue hacker"
    Then print current URL
