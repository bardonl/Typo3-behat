<?php

/**
 * Trait HelperContext this is for all the 'supportive' functions to keep FeatureContext neat and tidy.
 * @package HelperContext
 */
trait HelperContext
{
    /** Clicks on an ID or Class.
     * @param string $selector , the id or class name.
     * @When /^I click on the (?:class|id) "([^']*)"$/
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

    /** Takes a screenshot of the viewport, only works with the selenium2 driver(@javascript)
     * @Then I take a screenshot
     **/
    public function takeScreenshotOfPage()
    {
        $screenDir = getcwd() . '/screenshots/';
    
        print('You can find the screenshot(s) in ' . $screenDir . PHP_EOL);

        if (!is_dir($screenDir)) {
            mkdir($screenDir);
        }
        file_put_contents($screenDir . date('d-m-y') . ' - ' . uniqid() . '.png',
            $this->getSession()->getScreenshot());
    }

    /** Waits an x amount of seconds, only really useful for the selenium2 driver.
     * Example: Then I wait for "<seconds>" seconds
     * @param int $seconds
     * @Then /^I wait for (\d+) seconds$/
     * @And /^I wait for (\d+) seconds$/
     **/
    public function timer($seconds)
    {
        $this->getSession()->wait($seconds * 1000);
    }

    /** Reverses strings like: test_rotterdam to rotterdam_test, this is because RET needs that in its UID.
     * @param string $stringSwitchPlaces
     * @return string
     */
    public function reverseStringJourneyPlanner($stringSwitchPlaces)
    {
        if ($stringSwitchPlaces !== 'n-a') {
            return implode('_', array_reverse(explode('/', $stringSwitchPlaces)));
        }
    }

    /** Gets the value of an input field
     * Example: Then I get the value of "<field name>"
     * @param string $field
     * @Then /^I get the value of "([^']*)"$/
     */
    public function getInputValue($field)
    {
        print($this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]')->getValue());
    }

    /** Fills fields from an array input.
     * @param array $fieldAndValue
     */
    public function fillFieldsFromArray(array $fieldAndValue)
    {
        foreach ($fieldAndValue as $key => $value) {
            $this->getSession()->getPage()->find('css', 'input[name="' . $key . '"]')->setValue($value);
        }
    }

    /** Fills the hidden input that come from the feature files
     * Example: Then I fill the hidden input "<field name>" with "<value to set>"
     * @param string $field
     * @param string $value
     * @Then /^I fill the hidden input "([^']*)" with "([^']*)"$/
     */
    public function fillHiddenInputFromFeatureFiles($field, $value)
    {
        $this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]')->setValue($value);
    }
}
