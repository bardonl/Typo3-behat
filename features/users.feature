Feature: Users
  In order to use the website
  As a website user
  I need to be able to register/login

  Background:
    Given I am on "/home/mijn-ret.html"

  Scenario: I want to login
    When I fill in "tx_retusers_login[username]" with "kiwiMitchel"
    And I fill in "tx_retusers_login[password]" with "kaaskaas"
    Then I press "Inloggen"
    Then the url should match "mijn-ret.html"
    Then I should see "Welkom kaas van kaas"

  Scenario: I want to see my data
    Then the url should match "mijn-ret.html"
    When I follow "gegevens"
    Then I should see "Kaasstraat 1"


  Scenario: I want to logout
    Then the url should match "mijn-ret.html"
    When I follow "Uitloggen"
    Then the url should match "uitloggen.html"
    And I should see "Inloggen"

  Scenario: I want to do SQL injections
    When I fill in "tx_retusers_login[username]" with "\"; SHOW TABLES;"
    And I fill in "tx_retusers_login[password]" with "\"; SHOW TABLES"
    Then I press "Inloggen"
    Then I should see "onjuist!"
    When I fill in "tx_retusers_login[username]" with "'; SHOW TABLES;"
    And I fill in "tx_retusers_login[password]" with "'; SHOW TABLES"
    Then I press "Inloggen"
    Then I should see "onjuist!"
    When I fill in "tx_retusers_login[username]" with "\" or \"\"=\""
    And I fill in "tx_retusers_login[password]" with "\" or \"\"=\""
    Then I press "Inloggen"
    Then I should see "onjuist!"