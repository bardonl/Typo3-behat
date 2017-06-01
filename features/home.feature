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
    When I click on the class ".line-number--tram-8"
    Then the url should match "tram-8"
    Then I should see "Tram 8"
    When I click on the class ".HA1169"
    Then the url should match "pieter-de-hoochweg"
    Then I should see "Halte"

  Scenario: Check planned diversions
    Then I follow "geplande omleidingen"
    Then I should see "Omleidingen"

    @test
  Scenario: Can I see and click on some lines
    Given the following lines exist:
      | type   | lines |
      | Bus    | 37    |
      | Tram   | 8     |
      | Bobbus | b4    |
      | Bobbus | b2    |
      | Bobbus | b19   |
      | Boat   | ferry |
    Then I click on some random lines

  Scenario: Can I use the journey planner
    Given the following journeys exist:
      |     departure    |      via       |     arrival   |
      | rotterdam/veenoord |  s-gravendeel/biekurf |  rotterdam/pieter-de-hoochweg  |
      | rotterdam/spankerstraat |  n-a |  rotterdam/pieter-de-hoochweg  |
      | rotterdam/pieter-de-hoochweg |  heenvliet/leenmanstraat|  rotterdam/pieter-de-hoochweg  |
      | rotterdam/veenoord |  n-a |  rotterdam/pieter-de-hoochweg  |
      | rotterdam/spankerstraat |  heinenoord/jan-van-vlietstraat |  rotterdam/pieter-de-hoochweg  |

  Scenario: Can I use the travel product assistent
    Then I follow "Reisproductadviseur"
    Then I should see "Advies over het reisproduct dat het beste bij jou past"
    Then I press "Reisproductadviseur starten"
    Then I should see "Vul de vertrek- en aankomstlocatie"
    Then I follow "Regelmatig"
    Then I should see "Wat voor type reiziger ben jij?"
    Then I fill the hidden input "tx_retproducts_advisor[adviceRequest][departure][uid]" with "rotterdam/keizerswaard"
    Then I get the value of "tx_retproducts_advisor[adviceRequest][departure][uid]"
    Then I fill the hidden input "tx_retproducts_advisor[adviceRequest][arrival][uid]" with "rotterdam/pieter-de-hoochweg"
    Then I get the value of "tx_retproducts_advisor[adviceRequest][arrival][uid]"
    When I check "Trein" from "tx_retproducts_advisor[adviceRequest][modalities][]"
    Then I fill in "travels" with "20"
    Then I fill in "additionalCosts" with "0"
    Then I select "4" from "tx_retproducts_advisor[adviceRequest][age][__identity]"
    Then I press "Volgende stap"
    Then print current URL
    Then print last response
    Then I should see "Je advies"

  Scenario: I go to a page that doesn't exist
    Given I am on "bestaatniet.html"
    Then I should see "404"

  Scenario: I click on the menu links
    Then I follow "Zakelijk"
    Then the url should match "zakelijk.html"
    Then I follow "Home"
    Then I should be on the homepage
    Then I follow "Over de RET"
    Then the response status code should be 200
