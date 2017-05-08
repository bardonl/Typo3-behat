<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Class FeatureContext, houses all of the custom context for the feature files.
 * Author: Mitchel van Hamburg <vanhamburg@redkiwi.nl>, Redkiwi
 */
class FeatureContext extends MinkContext implements Context
{
    use HelperContext;

    /**
     * The CSS selectors for types and lines from the RET site. Used by the "seeTheLines" function
     * @var array
     */
    public $typeAndLines;

    /** Waits for a suggestionbox to appear under a input field.
     * @param string $locator
     * @param boolean $clickOnFirstSuggestion
     * @Then /^I wait for the suggestion box with "([^']*)" to appear$/
     **/
    public function suggestionBoxTimer($locator, $clickOnFirstSuggestion = false)
    {
        $this->getSession()->wait(5000, '$(\'' . $locator . '\').children().length > 2');
        if ($clickOnFirstSuggestion === true) {
            $firstChild = $this->getSession()->getPage()->find('css', $locator . ':first-child');
            if ($firstChild) {
                $this->getSession()->getPage()->clickLink($firstChild);
            } else {
                print('Couldn\'t find the first child of locator: ' . $locator);
            }
        }
    }

    /** Checks if we can see lines in the dienstregelingen
     * Example: Given the following lines exist:
     *          | type | lines |
     *          | Tram |  23   |
     *          | bus  |  713  |
     * @param TableNode $linesTable
     * @Given the following lines exist:
     **/
    public function seeTheLines(TableNode $linesTable)
    {
        $i = 0;
        foreach ($linesTable->getHash() as $linesHash) {
            $i++;
            $this->typeAndLines[$i] = '.line-number--' . strtolower($linesHash['type']) . '-' . $linesHash['lines'];
        }
        $element = $this->getSession()->getPage()->find('css',
            '.line-number--' . strtolower($linesHash['type']) . '-' . $linesHash['lines']);

        if ($element === null) {
            throw new \InvalidArgumentException(sprintf('Cannot find line %s of type %s', $linesHash['lines'],
                $linesHash['type']));
        }
    }

    /** Gets the lines from the typeAndLines array, clicks and tests them for a response.
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
                print('URL response code: ' . $this->getSession()->getStatusCode() . ', URL of tested line: ' . $this->getSession()->getCurrentUrl() . PHP_EOL);
            }
        } else {
            throw new \InvalidArgumentException('Type and Lines array is empty, did you run. Given the following lines exist: prior?');
        }
    }

    /** This gets a random line (only one since thats the max ret supports for now) from the typeAndLines array and then adds it to the favourites.
     * @Then I add the line to my favourites
     */
    public function addToFavourites()
    {
        if (!empty($this->typeAndLines)) {
            $randomLine = $this->typeAndLines[mt_rand(1, count($this->typeAndLines))];

            $this->clickOnClassOrId($randomLine);
            $this->clickOnClassOrId('.btn--favorite');
            $this->visit('/');
            $this->assertPageContainsText('Favoriete lijnen');
        } else {
            throw new InvalidArgumentException('Type and Lines array is empty. Did you run Given the following lines exist, prior?');
        }
    }

    /**
     * @Then /^I test if line (\d+) of type "([^']*)" near me$/
     */
    function linesNearMe($lineNumber, $lineType)
    {
        //represent nth child of parent div in the dienstregeling id.
        $nthChildType = [
            'bus' => 1,
            'tram' => 2,
            'metro' => 3,
            'ferry' => 4,
            'bobbus' => 5,
        ];

        foreach ($nthChildType as $key => $value) {
            if ($lineType === $key) {
                $xPathLocator = '//*[@id="panel-dienstregeling"]/div/div[2]/div[' . $value . ']/div[4]/div[1]';
                print($xPathLocator);
            }
        }
        if ($this->getSession()->getPage()->find('xpath', $xPathLocator)->getText() === $lineNumber) {
            print("Line number found!");
        } else {
            throw new Exception("Line was not found..");
        }
    }

    /** Searches for a string on the RET site.
     * @param string $searchCriteria
     * @Then /^I search the RET site with "([^']*)"$/
     */
    public function searchOnRET($searchCriteria)
    {
        $base_url = 'https://www.ret.nl/zoekresultaten.html?__referrer%5B%40extension%5D=&__referrer%5B%40controller%5D=Standard&__referrer%5B%40action%5D=index&__referrer%5Barguments%5D=YTowOnt9530c493ba004cf1caf123ffd1044ee4efb5d5375&__referrer%5B%40request%5D=a%3A3%3A%7Bs%3A10%3A%22%40extension%22%3BN%3Bs%3A11%3A%22%40controller%22%3Bs%3A8%3A%22Standard%22%3Bs%3A7%3A%22%40action%22%3Bs%3A5%3A%22index%22%3B%7D40d8b6f2582bb319cdc6822b8191d6122c5b0636&__trustedProperties=a%3A0%3A%7B%7D19ce2f840cb5d040168e337ca7768ade4283e5bb&q=';
        $spacesToPlus = str_replace(' ', '+', $searchCriteria);
        $this->visit($base_url . $spacesToPlus);
        if ($this->assertResponseStatus(200)) {
            throw new \InvalidArgumentException(sprintf('Error: search page returned 404.'));
        }
    }

    /** This tests the journeyplanner form on the homepage of RET.
     * @param TableNode $departureAndArrival
     * @Given the following journeys exist:
     */
    public function journeyPlanner(TableNode $departureAndArrival)
    {
        $i = 0;
        foreach ($departureAndArrival->getHash() as $depAndArrHash) {
            $departures[$i] = $depAndArrHash['departure'];
            $via[$i] = $depAndArrHash['via'];
            $arrivals[$i] = $depAndArrHash['arrival'];
            $i++;
        }

        for ($journeyIndex = 0; $journeyIndex < count($departureAndArrival->getHash()); $journeyIndex++) {
            $this->fillFieldsFromArray([
                'tx_retjourneyplanner_form[search][departure][uid]' => $departures[$journeyIndex],
                'tx_retjourneyplanner_form[search][via][uid]' => $via[$journeyIndex],
                'tx_retjourneyplanner_form[search][arrival][uid]' => $arrivals[$journeyIndex],
                'tx_retjourneyplanner_form[search][requestType]' => mt_rand(0, 1),
                'tx_retjourneyplanner_form[search][date]' => date('y-m-d'),
                'tx_retjourneyplanner_form[search][time]' => date('H:m'),
                'tx_retjourneyplanner_form[search][travelOption]' => mt_rand(1, 3),
            ]);

            $this->getSession()->getPage()->pressButton('Nu bekijken');

            $depart = $this->reverseStringJourneyPlanner($departures[$journeyIndex]);
            $vias = $this->reverseStringJourneyPlanner($via[$journeyIndex]);
            $arrivs = $this->reverseStringJourneyPlanner($arrivals[$journeyIndex]);

            $this->assertUrlRegExp('/' . $depart . '/');
            $this->assertUrlRegExp('/' . $vias . '/');
            $this->assertUrlRegExp('/' . $arrivs . '/');

            $this->assertPageContainsText('Totale reistijd');

            print('Resulting URL: ');
            print($this->printCurrentUrl() . PHP_EOL);
        }
    }

    /** Goes to the login page and logs in to the RET site.
     * @param string $username
     * @param string $password
     * @Then /^I login to ret with "([^']*)" and "([^']*)"$/
     */
    public function loginToRet($username, $password)
    {
        $this->visit('https://www.ret.nl/home/mijn-ret/');
        $this->fillFieldsFromArray([
            'tx_retusers_login[username]' => $username,
            'tx_retusers_login[password]' => $password
        ]);
        $this->pressButton('Inloggen');
    }
}
