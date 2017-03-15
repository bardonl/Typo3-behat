Feature: Homepage
	In order to see the homepage
	As a website user
	I need to be able to see and interact with the homepage

	@noJS
	Scenario: I want to see the blog
		Given I am on the homepage
		Then I should see "Blog"
		When I follow "Blog"
		Then the url should match "blog.html"

	@javascript
	Scenario: Can I see all bus lines
		Given I am on the homepage
		When I click on the class "expand"
		Then I wait for 1 seconds
		Then I should see "713"
		Then I take a screenshot

	@javascript
	Scenario: Can I see the Reisplanner
		Given I am on the homepage
		When I click on the id 'aria-panel-reisplanner'
		Then I wait for 2 seconds
		Then I should see "Van waar vertrek je"
		When I fill in "voer je halte of straatnaam in" with "Keizerswaard, Rotterdam"
		Then I wait for the suggestion box to appear on reisplanner page

	@javascript
	Scenario: I want to see lines near me
		Given I am on the homepage
		When I click on the id "aria-panel-dienstregeling"
		When I click on the class "js-geolocation-toggle"
		Then I wait for 3 seconds
		Then I should see "8" in the "line-overview__line--close" element
		Then I take a screenshot