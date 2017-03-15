Feature: Homepage
	In order to see the homepage
	As a website guest
	I need to be able to see and interact with the homepage

	@noJS
	Scenario: I want to see the blog
		Given I am on the homepage
		Then I should see "Blog"
		When I follow "Blog"
		Then the url should match "blog.html"
		Then I should see "De leukste verhalen uit en over het OV"
	
	@noJS
	#TODO make this randomly check one somehow..
	Scenario: Check out a tram line
		Given I am on the homepage
		When I click on the selector ".line-number--tram-8"
		Then the url should match "tram-8"
		Then I should see "Tram 8"
		When I click on the selector ".HA1169"
		Then the url should match "pieter-de-hoochweg"
		Then I should see "Halte"

	@noJS
	#TODO actually make this work
	Scenario Outline: Can I see all lines
		Given I have the number <lines>
		Examples:
			| lines |
			|   2	|
			|   4	|
			|   7	|
			|   8	|
			|   12	|
			|   20	|
			|   21	|
			|   23	|
			|   24	|
			|   25	|

	@javascript
	Scenario: Can I see and use the Reisplanner
		Given I am on the homepage
		When I click on the selector "#aria-panel-reisplanner"
		Then I wait for 2 seconds
		Then I should see "Van waar vertrek je"
		When I fill in "voer je halte of straatnaam in" with "Keizerswaard, Rotterdam"
		Then I click on the selector ".reisplanner__address"
		Then I wait for 3 seconds
		When I fill in "voer je halte of straatnaam in" with "Pieter de Hoochweg, Rotterdam"
		Then I click on the selector ".input--address--arrival"
		Then I wait for 3 seconds
		When I press "button_form_submit"
		Then the url should match "reisplanner/details/"
		Then I should see "VERTREK"

	@javascript
	Scenario: I want to see lines near me
		Given I am on the homepage
		When I click on the selector "#aria-panel-dienstregeling"
		Then I wait for 3 seconds
		When I click on the selector ".js-geolocation-toggle"
		Then I wait for 5 seconds
		Then I should see "8" in the "line-overview__line--close" element
		Then I take a screenshot