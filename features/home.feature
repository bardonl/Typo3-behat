Feature: Homepage
  In order to see the homepage
  As a website guest
  I need to be able to use the homepage

  Background:
    Given I am on the homepage

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

  Scenario: Can I use the journey planner
    Given the following journeys exist:
      |     departure    |      via       |     arrival   |
      | rotterdam/spankerstraat |  s-gravendeel/biekurf |  rotterdam/pieter-de-hoochweg  |
      | rotterdam/spankerstraat |  n-a |  rotterdam/pieter-de-hoochweg  |
      | rotterdam/spankerstraat |  heenvliet/leenmanstraat|  rotterdam/pieter-de-hoochweg  |
      | rotterdam/spankerstraat |  n-a |  rotterdam/pieter-de-hoochweg  |
      | rotterdam/spankerstraat |  heinenoord/jan-van-vlietstraat |  rotterdam/pieter-de-hoochweg  |

  @test
  Scenario: Can I use the travel product assistent
    Then I follow "Reisproductadviseur"
    Then print current URL
    Then I should see "Advies over het reisproduct dat het beste bij jou past"
    Then print current URL
    Then I press "Reisproductadviseur starten"
    Then print current URL
    Then I should see "Vul de vertrek- en aankomstlocatie"
    Then I follow "Regelmatig"
    Then print current URL
    Then I should see "Wat voor type reiziger ben jij?"
    Then I fill the hidden input "tx_retproducts_advisor[adviceRequest][departure][uid]" with "rotterdam/keizerswaard"
    Then I fill the hidden input "tx_retproducts_advisor[adviceRequest][arrival][uid]" with "rotterdam/pieter-de-hoochweg"
    Then I get the value of "tx_retproducts_advisor[adviceRequest][arrival][uid]"
    When I check "Tram" from "tx_retproducts_advisor[adviceRequest][modalities][]"
    Then I fill in "travels" with "20"
    Then I fill in "additionalCosts" with "0"
    Then I select "4" from "tx_retproducts_advisor[adviceRequest][age][__identity]"
    Then I press "Volgende stap"
    Then print current URL
    Then print last response
    Then I should see "Je advies"

  @test
  Scenario: I go to a page that doesn't exist
    Given I am on "bestaatniet.html"
    Then I should see "404"
