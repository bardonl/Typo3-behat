<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context    {

    /**
    *   @Then /^I wait for the suggestion box to appear on reisplanner page$/
    */
    public function iWaitForTheSuggestionBoxToAppearOnSearchPage()  {
        $this->getSession()->wait(5000, "$('').children().length > 0");
    }

    //TODO: Make a single function for the 'iClickOnThe*'.
    /** Clicks on classes
    *   @When /^I click on the class "([^"]*)"$/
    **/
    public function iClickOnTheClass($class)  {
        $locator = ".". $class;
        $element = $this->getSession()->getPage()->find('css', $locator);

        if(null == $element)    {
            throw new \InvalidArgumentException(sprintf('Cannot find class selector "%s"', $class));
        } else {
            $element->click();
        }
    }

    /** Clicks on ids
    *   @When /^I click on the id "([^"]*)"$/
    **/
    public function iClickOnTheId($id)  {
        $locator = "#". $id;
        $element = $this->getSession()->getPage()->find('css', $id);

        if(null == $element)    {
            throw new \InvalidArgumentException(sprintf('Cannot find id selector "%s" you idiot.', $id));
        } else {
            $element->click();
        }
    }

    /** This is needed because the RET site has some fancy ass animations, and because all the actions take place right after eachother they will return an error if the animation has not finished.
    *   @Then /^I wait for (\d+) seconds$/
    **/
    public function iWaitAnAmount($milisecs)    {
        $this->getSession()->wait($milisecs * 1000);
    }

    /**
    *   @Then I take a screenshot
    **/
    public function takeScreenshotOfPage()  {
        $this->getSession()->maximizeWindow();
        file_put_contents('/Users/mitch/Sites/MinkFromScratch/screenshots/'. date('d-m-y') . ' - ' .microtime(true) .'.png', $this->getSession()->getScreenshot());
    }

























}