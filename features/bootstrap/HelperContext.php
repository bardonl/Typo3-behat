<?php

/**
 * Class HelperContext this is for all the 'supportive' functions to keep FeatureContext neat and tidy.
 * @package HelperContext
 */
trait HelperContext
{
    /** Clicks on an ID or Class.
     * @param string $selector , the id or class name.
     * @When /^I click on the selector "([^']*)"$/
     **/
    public function clickOnClassOrId($selector)
    {
        $selectorType = substr($selector, 0, 1);
        switch ($selectorType) {
            case '#':
                $noHashtag = str_replace('#', '', $selector);
                $element = $this->getSession()->getPage()->findById($noHashtag);
                break;
            case '.':
                $element = $this->getSession()->getPage()->find('css', $selector);
                break;
            default:
                throw new \InvalidArgumentException(
                    'Cannot determine if it\'s an ID or class. Did you place a "." or "#" in front of the selector?'
                );
                break;
        }

        if ($element === null) {
            throw new \InvalidArgumentException(sprintf('Cannot find class or id with %s', $selector));
        } else {
            $element->click();
        }
    }

    /**
     * @Then I take a screenshot
     **/
    public function takeScreenshotOfPage()
    {
        $screenDir = getcwd() . '/screenshots/';
        print('You can find the screenshot(s) in ' . $screenDir . PHP_EOL);

        if (!is_dir($screenDir)) {
            mkdir($screenDir);
        }
        file_put_contents($screenDir . date('d-m-y') . ' - ' . microtime(true) . '.png',
            $this->getSession()->getScreenshot());
    }

    /** This is needed because the RET site has some fancy animations, and because all the actions take place right
     *  after each other they will return an error if the animation/loading has not finished.
     * @param int $seconds
     * @Then /^I wait for (\d+) seconds$/
     * @And /^I wait for (\d+) seconds$/
     **/
    public function timer($seconds)
    {
        $this->getSession()->wait($seconds * 1000);
    }

    /**
     * This function is mostly needed for the journeyplanner tests
     * @param $field string
     * @param $value string
     * @Then /^I fill the hidden input "([^']*)" with "([^']*)"$/
     */
    public function fillHiddenInput($field, $value)
    {
        $this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]')->setValue($value);
    }

    /**
     * @param $string string
     * @return string
     */
    public function reverseStringJourneyPlanner($string)
    {
        if ($string !== 'n-a') {
            return implode('_', array_reverse(explode('/', $string)));
        }
    }

    /**
     * @Then /^I get the value of "([^']*)"$/
     * @If "([^']*)"
     */
    public function getInputValue($field)
    {
        print($this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]')->getValue());
    }
}