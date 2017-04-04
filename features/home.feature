Feature: Homepage
  In order to see the homepage
  As a website guest
  I need to be able to see and interact with the homepage

  Background:
    Given I am on the homepage
    Then I should not see "Nederlandse versie"
    Then I should see "English version"


  Scenario: Can I go to the homepage
    Then I should see "Dienstregeling"

  Scenario: I want to visit the blog
    Then I should see "Blog"
    When I follow "Blog"
    Then the url should match "blog.html"
    Then I should see "De leukste verhalen uit en over het OV"

  Scenario: Check out a tram line
    When I click on the selector ".line-number--tram-8"
    Then the url should match "tram-8"
    Then I should see "Tram 8"
    When I click on the selector ".HA1169"
    Then the url should match "pieter-de-hoochweg"
    Then I should see "Halte"

  Scenario: Can I see and click on some lines
    Given the following lines exist:
      | type   | lines |
      | Bus    | 37    |
      | Bus    | 70    |
      | Bus    | 174   |
      | Bus    | 121   |
      | Bus    | 713   |
      | Tram   | 4     |
      | Tram   | 23    |
      | Tram   | 7     |
      | Tram   | 25    |
      | Tram   | 8     |
      | Bobbus | b4    |
      | Bobbus | b2    |
      | Bobbus | b19   |
      | Boat   | ferry |
    Then I click on some random lines

#  Scenario: I want to see lines near me
#    When I click on the selector ".js-geolocation-toggle"
#    Then I should see "8" in the "line-overview__line--close" element

  Scenario: Can I see and use the reisplanner
      When I fill in "input--address-departure" with "Keizerswaard, Rotterdam"
      When I fill in "input--address-arrival" with "Pieter de Hoochweg, Rotterdam"
      When I press "Nu bekijken"
      Then print current URL
      Given I am on ""
      Then I should see "Vertrek:"
      Then the url should match "reizen/reisplanner/n-a/n-a/n-a/"
      Given I am on ""
      Then I should see "Vertrek:"
      And I should see "Aankomst"

  @javascript
  Scenario: Can I see and use the reisplanner
    Then I should see "Dienstregeling"
    Then I take a screenshot