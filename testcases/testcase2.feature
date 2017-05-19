Feature: Testcase 2
  In order to see and use the journey planner
  As a website guest
  I need to be able to use the journey planner

  Background:
    Given I am on the homepage


  Scenario: Then I test the journey planner non-GUI
    Given the following journeys exist:
      | departure                | via                  | arrival                      | time |
      | rotterdam/veenoord       | s-gravendeel/biekurf | rotterdam/pieter-de-hoochweg | 1200 |
      | rotterdam/spankerstraat  | n-a                  | rotterdam/pieter-de-hoochweg | now  |
      | vlaardingen/esdoorndreef | n-a                  | rotterdam/keizerswaard       | now  |

  @javascript @test
  Scenario: I want to select a line from the dropdown menu
    Then I click on the id "#aria-panel-reisplanner"

    Then I fill in "input--address-departure" with "Esdoorndreef"
    Then I click on the id "#input--address-departure"
    Then I wait for the suggestions at locator "#custom-form-select-options-departure" to appear and I "do" click on the first suggestion
    Then I wait for 2 seconds
    Then print current URL

  @javascript @test
  Scenario: Then I clear the field
    Then I click on the id "#aria-panel-reisplanner"

    Then I fill in "input--address-departure" with "kaas"
    Then I click on the id "#input--address-departure"
    Then I wait for 1 seconds
    Then I click on the xp "//*[@id="panel-reisplanner"]/form/div[3]/div[1]/div/div/div[2]/div/span/span/div/span"
    Then the "#input--address-departure" element should not contain "kaas"