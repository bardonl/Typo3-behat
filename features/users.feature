Feature: Users
  In order to use the website
  As a website user
  I need to be able to register/login

  Background:
    Given I am on "/home/mijn-ret.html"

  Scenario: I want to see my data
    Then I login to ret with "kiwiMitchel" and "kaaskaas"
    Then print current URL
    Then I should see "Kaasstraat 1"

  Scenario: I want to logout
    Then I login to ret with "kiwiMitchel" and "kaaskaas"
    Then print current URL
    When I follow "Uitloggen"
    Then print current URL
    And I should see "Inloggen"

  @test
  Scenario: I want to do SQL injections
    Then print current URL
    When I fill in "tx_retusers_login[username]" with "\"; SHOW TABLES;"
    And I fill in "tx_retusers_login[password]" with "\"; SHOW TABLES"
    Then print current URL
    Then I press "Inloggen"
    Then print current URL
    Then I should see "onjuist"
    When I fill in "tx_retusers_login[username]" with "'; SHOW TABLES;"
    And I fill in "tx_retusers_login[password]" with "'; SHOW TABLES"
    Then I press "Inloggen"
    Then I should see "onjuist"
    When I fill in "tx_retusers_login[username]" with "\" or \"\"=\""
    And I fill in "tx_retusers_login[password]" with "\" or \"\"=\""
    Then I press "Inloggen"
    Then I should see "onjuist"
