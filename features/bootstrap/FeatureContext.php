<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**+
 * Class FeatureContext, houses all of the custom context for the feature files.
 * Author: Mitchel van Hamburg <vanhamburg@redkiwi.nl>, Redkiwi
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * The CSS selectors for types and lines from the RET site.
     * @var array
     */
    public $typeAndLines;

    /**
     * @Then /^I wait for the suggestion box with "([^']*)" to appear$/
     * @param $locator, the CSS class for locating the box that contains the suggestions
     **/
    public function suggestionBoxTimer($locator)
    {
        $this->getSession()->wait(5000, '$(' . $locator . ').children().length > 0');
    }

    /** Clicks on an ID or Class.
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
                throw new \InvalidArgumentException('Cannot determine if it\'s an ID or class. Did you place a "." or "#" in front of the selector?');
                break;
        }

        if ($element === null) {
            throw new \InvalidArgumentException(sprintf('Cannot find class or id with %s', $selector));
        } else {
            $element->click();
        }
    }

    /** This is needed because the RET site has some fancy ass animations, and because all the actions take place right after each other they will return an error if the animation/loading has not finished.
     * @param  int $milisecs
     * @Then /^I wait for (\d+) seconds$/
     * @And /^I wait for (\d+) seconds$/
     **/
    public function timer($milisecs)
    {
        //Only needed if in use with the Selenium2 driver.
        $this->getSession()->wait($milisecs * 1000);
    }

    /**
     * @Then I take a screenshot
     **/
    public function takeScreenshotOfPage()
    {
        //Only works with the Selenium driver.
        $screenDir = getcwd() . '/screenshots';
        print('You can find the screenshot(s) in ' . $screenDir);

        if (!is_dir($screenDir)) {
            mkdir($screenDir);
        }
        file_put_contents($screenDir . '/' . date('d-m-y') . ' - ' . microtime(true) . '.png', $this->getSession()->getScreenshot());
    }

    /** Checks if the following lines exist on the page.
     * @Given the following lines exist:
     **/
    public function seeTheLines(TableNode $linesTable)
    {
        $i = 0;
        foreach ($linesTable->getHash() as $linesHash) {
            $lines[] = $linesHash;
            $i++;
            $this->typeAndLines[$i] = '.line-number--' . strtolower($linesHash['type']) . '-' . $linesHash['lines'];
        }
        $element = $this->getSession()->getPage()->find('css', '.line-number--' . strtolower($linesHash['type']) . '-' . $linesHash['lines']);

        if ($element === null) {
            throw new \InvalidArgumentException(sprintf('Cannot find line %s of type %s', $linesHash['lines'], $linesHash['type']));
        }
    }

    /** Clicks on the lines links and gives back the URL(for now)
     * @Then I click on some random lines
     */
    public function clickOnRandomLines()
    {
        if (!empty($this->typeAndLines)) {
            for ($j = 0; $j <= count($this->typeAndLines); $j++) {
                $randomNumbers[] = mt_rand(1, count($this->typeAndLines));
            }

            $uniqueRandomNumbers = array_unique($randomNumbers);
            $indexArrayKeysNumerically = array_values($uniqueRandomNumbers);

            for ($i = 0; $i < count($indexArrayKeysNumerically); $i++) {
                $this->clickOnClassOrId($this->typeAndLines[$indexArrayKeysNumerically[$i]]);
                print('URL response code: ' . $this->getSession()->getStatusCode() . ', URL of tested line: ' . $this->getSession()->getCurrentUrl() . "\n");
            }
        } else {
            throw new \InvalidArgumentException('Type and Lines array is empty, did you run. Given the following lines exist: prior?');
        }
    }

    /** This searches on the RET site, this is needed because otherwise it returns an error.
     * @Then /^I search the RET site with "([^']*)"$/
     */
    public function searchOnRET($searchCriteria)   {
        $base_url = "https://www.ret.nl/zoekresultaten.html?__referrer%5B%40extension%5D=&__referrer%5B%40controller%5D=Standard&__referrer%5B%40action%5D=index&__referrer%5Barguments%5D=YTowOnt9530c493ba004cf1caf123ffd1044ee4efb5d5375&__referrer%5B%40request%5D=a%3A3%3A%7Bs%3A10%3A%22%40extension%22%3BN%3Bs%3A11%3A%22%40controller%22%3Bs%3A8%3A%22Standard%22%3Bs%3A7%3A%22%40action%22%3Bs%3A5%3A%22index%22%3B%7D40d8b6f2582bb319cdc6822b8191d6122c5b0636&__trustedProperties=a%3A0%3A%7B%7D19ce2f840cb5d040168e337ca7768ade4283e5bb&q=";
        $spacesToPlus = str_replace(' ', '+', $searchCriteria);
        $this->visit($base_url . $spacesToPlus);
        if($this->assertResponseStatus(200))    {
            throw new \InvalidArgumentException(sprintf("Error: search page returned 404."));
        }
    }
}
