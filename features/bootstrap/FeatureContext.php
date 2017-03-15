<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context    
{
    /**
    *   @Then /^I wait for the suggestionbox with "([^"]*)" to appear$/
    */
    public function WaitForTheSuggestionBoxToAppearOnPage($locator)  
    {
        $this->getSession()->wait(5000, "$('". $locator ."').children().length > 0");
    }


    /** Clicks on an ID or Class.
    *   @When /^I click on the selector "([^"]*)"$/
    **/
    public function ClickOnClassOrId($selector)	
    {
    	$whatisit = substr($selector, 0, 1);
    	switch ($whatisit) {
    		case '#':
    			$noHashtag = str_replace('#', '', $selector);
    			$element = $this->getSession()->getPage()->findById($noHashtag);
    			break;
    		case '.':
    			$element = $this->getSession()->getPage()->find('css', $selector);
    			break;
    		default:
    			throw new \InvalidArgumentException("Cannot determine if it's an ID or class. Did you place an '.' or '#' in front of the selector?");
    			break;
    	}

    	if(null == $element)	
    	{
    		throw new \InvalidArgumentException(sprintf('Cannot find class or id with "%s"', $selector));
    	} else {
    		$element->click();
    	}
    }

    //get the thing it needs to click on, substr that and check if its an # or . and on that basis make it click an id

    /** This is needed because the RET site has some fancy ass animations, and because all the actions take place right after eachother they will return an error if the animation/loading has not finished.
    *   @Then /^I wait for (\d+) seconds$/
    **/
    public function WaitAnAmount($milisecs)    
    {
        $this->getSession()->wait($milisecs * 1000);
    }

    /**
    *   @Then I take a screenshot
    **/
    public function takeScreenshotOfPage()  
    {
        $currentdir = getcwd();
        $dirname = 'screenshots';
                print("You can find the screenshots in ". $currentdir .'/'. $dirname);
        if(!is_dir($currentdir .'/'. $dirname))    {
            mkdir($currentdir .'/'. $dirname);
        }
        $this->getSession()->maximizeWindow(); //Doesn't work properly on macOS.
        file_put_contents($currentdir .'/'. $dirname .'/'. date('d-m-y') .' - '. microtime(true) .'.png', $this->getSession()->getScreenshot());
    }
}