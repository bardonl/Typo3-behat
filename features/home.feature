Feature: Homepage
  In order to see the homepage
  As a website guest
  I need to be able to see and interact with the homepage

  Background:
    Given I am on the homepage
    Then I should not see "Nederlandse versie"
    Then I should see "English version"


  @noJS
  Scenario: Can I go to the homepage
    Then I should see "Dienstregeling"

  @noJS
  Scenario: I want to visit the blog
    Then I should see "Blog"
    When I follow "Blog"
    Then the url should match "blog.html"
    Then I should see "De leukste verhalen uit en over het OV"

  @noJS
  Scenario: Check out a tram line
    When I click on the selector ".line-number--tram-8"
    Then the url should match "tram-8"
    Then I should see "Tram 8"
    When I click on the selector ".HA1169"
    Then the url should match "pieter-de-hoochweg"
    Then I should see "Halte"

  @test
  Scenario: Can I see and click on some lines
    Given the following lines exist:
    | type   | lines  |
    | Bus    |  37    |
    | Bus    |  70    |
    | Bus    |  174   |
    | Bus    |  121   |
    | Bus    |  713   |
    | Tram   |  4     |
    | Tram   |  23    |
    | Tram   |  7     |
    | Tram   |  25    |
    | Tram   |  8     |
    | Bobbus |  b4    |
    | Bobbus |  b2    |
    | Bobbus |  b19   |
    | Boat   | ferry  |
    Then I click on some random lines

  @noJS
  Scenario: I want to see lines near me
    When I click on the selector ".js-geolocation-toggle"
    Then I should see "8" in the "line-overview__line--close" element


  @javascript
  Scenario: Can I see and use the Reisplanner
    Given I am on the homepage
    When I click on the selector "#aria-panel-reisplanner"
    Then I wait for 2 seconds
    Then I take a screenshot
    When I fill in "input--address-departure" with "Keizerswaard, Rotterdam"
    Then I click on the selector "#input--address-departure"
    Then I wait for 2 seconds
    When I click on the selector "#aria-panel-reisplanner"
    When I fill in "input--address-arrival" with "Pieter de Hoochweg, Rotterdam"
    Then I click on the selector "#input--address-arrival"
    And I wait for 2 seconds
    When I click on the selector "#aria-panel-reisplanner"
    Then I scroll "reisplanner__step" into view
    And I wait for 3 seconds
    When I press "Nu bekijken"
    Then the url should match "reisplanner/details/"
    Then I should see "VERTREK"