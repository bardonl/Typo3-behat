<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context    
{
    /**
    *   @Then /^I wait for the suggestion box with "([^"]*)" to appear$/
    */
    public function suggestionBoxTimer($locator)  
    {
		$this->getSession()->wait(5000, "$('". $locator ."').children().length > 0");
    }


    /** Clicks on an ID or Class.
    *   @When /^I click on the selector "([^"]*)"$/
    **/
    public function clickOnClassOrId($selector)	
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
    			throw new \InvalidArgumentException("Cannot determine if it's an ID or class. Did you place a '.' or '#' in front of the selector?");
    			break;
    	}

    	if(null == $element)	
    	{
    		throw new \InvalidArgumentException(sprintf('Cannot find class or id with "%s"', $selector));
    	} else {
    		$element->click();
    	}
    }

    /** This is needed because the RET site has some fancy ass animations, and because all the actions take place right after each other they will return an error if the animation/loading has not finished.
    *   @Then /^I wait for (\d+) seconds$/
    *   @And /^I wait for (\d+) seconds$/
    **/
    public function timer($milisecs)    
    {
        $this->getSession()->wait($milisecs * 1000);
    }

    /**
    *   @Then I take a screenshot
    **/
    public function takeScreenshotOfPage()  
    {
        $screendir = getcwd() .'/screenshots';
        print("You can find the screenshot(s) in ". $screendir);
        if(!is_dir($screendir))    {
			mkdir($screendir);
        }
        file_put_contents($screendir .'/'. date('d-m-y') .' - '. microtime(true) .'.png', $this->getSession()->getScreenshot());
    }

    /** 
    *	@Given the following lines exist:
    **/
    public function seeTheLines(TableNode $linesTable)	{
    	//Possible feature: get the lines available from the site and randomly click on some.
    	foreach ($linesTable->getHash() as $linesHash) {
    		$lines[] = $linesHash;
    	}
    	for ($i=0; $i < count($lines); $i++) { 
    		$stringIt = implode('', $lines[$i]);
    		$element = $this->getSession()->getPage()->find('css', '.line-number--tram-'. $stringIt);

    		if(null === $element)	{
    			print(sprintf("Cannot find line %s", $stringIt));
    		}
    	}
    }
}