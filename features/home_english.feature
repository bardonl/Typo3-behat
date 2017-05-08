Feature: English
  In order to use the website
  As an English user
  I need to be able to use and understand the website

  Background:
    Given I am on the homepage
    Then I follow "English version"

  Scenario: I want to login as an english user
    Then I should see "Timetable"
    Then I should not see "Dienstregeling"

  Scenario: I want to see the lines and check them out
    Given the following lines exist:
      | type | lines |
      | Bus  | 37    |
      | Bus  | 70    |
      | Bus  | 174   |
      | Bus  | 121   |
      | Bus  | 713   |
      | Tram | 4     |
      | Tram | 23    |
      | Tram | 7     |
      | Tram | 25    |
      | Tram | 8     |
    Then I click on some random lines

  Scenario: I check out a bus line
    When I click on the class ".line-number--bus-51"
    Then the url should match "bus-51.html"
    Then print current URL
    Then I should see "Bus 51"
    When I click on the class ".HA3027"
    Then the url should match "koekoekslaan"
    And I should see "Halte"
