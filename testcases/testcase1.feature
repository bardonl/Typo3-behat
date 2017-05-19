Feature: Testcase 1
  In order to see the homepage
  As a website guest
  I need to be able to use the homepage

  Background:
    Given I am on the homepage

  @javascript
  Scenario: Can I see and click on some lines
    Given the following lines exist:
      | type   | lines |
      | Tram   | 23    |
      | Bus    | 37    |
      | Tram   | 8     |
      | Bobbus | b4    |
      | Bobbus | b2    |
      | Bobbus | b19   |
      | Boat   | ferry |
    #Then I check time of lines with time "22:00" and all days of the week
    Then I click on the class ".line-number--tram-23"
    Then I click on folder
    Then I choose different stops and download the folder
