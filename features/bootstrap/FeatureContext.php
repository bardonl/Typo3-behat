<?php
use Behat\Gherkin\Node\TableNode;

/**
 * Trait FeatureContext, houses all of the custom context for the feature files.
 * Author: Mitchel van Hamburg <vanhamburg@redkiwi.nl>, Redkiwi
 */
trait FeatureContext
{
    use HelperContext;

    /**
     * The CSS selectors for types and lines from the RET site. Used by the "seeTheLines" function
     * @var array
     */
    public $typeAndLines;

    /** Waits for a suggestionbox to appear under an input field.
     * @param string $locator
     * @param string $clickOnFirstSuggestion
     * @Then /^I wait for the suggestions at locator "([^']*)" to appear and I "([^']*)" click on the first suggestion$/
     **/
    public function suggestionBoxTimer($locator, $clickOnFirstSuggestion = 'dont')
    {
        if ($this->getSession()->wait(5000, '$(\'' . $locator . '\').children().length > 0')) {
            if ($clickOnFirstSuggestion === 'do') {
                $firstSuggestion = $this->getSession()->getPage()->find('css',
                    $locator .' > span:nth-child(1)');
                $firstSuggestion->click();
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

            $indexArray = $this->getIndexes();

            for ($i = 0; $i < count($indexArray); $i++) {
                $this->clickOnClassOrId($this->typeAndLines[$indexArray[$i]]);

                $this->visit('/');
            }
        } else {
            throw new \InvalidArgumentException('Type and Lines array is empty, did you run. Given the following lines exist: prior?');
        }
    }

    /** This gets a random line (only one since thats the max ret supports for now) from the typeAndLines array and then adds it to the favourites.
     * @throws InvalidArgumentException
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
     * @param string $lineType
     * @param string $lineNumber
     * @throws Exception
     * @Then /^I test if line (\d+) of type "([^']*)" is near me$/
     */
    public function linesNearMe($lineNumber, $lineType)
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
            $time[$i] = $depAndArrHash['time'];
            $i++;
        }

        for ($journeyIndex = 0; $journeyIndex < count($departureAndArrival->getHash()); $journeyIndex++) {
            $this->fillFieldsFromArray([
                'tx_retjourneyplanner_form[search][departure][uid]' => $departures[$journeyIndex],
                'tx_retjourneyplanner_form[search][via][uid]' => $via[$journeyIndex],
                'tx_retjourneyplanner_form[search][arrival][uid]' => $arrivals[$journeyIndex],
                'tx_retjourneyplanner_form[search][requestType]' => mt_rand(0, 1),
                'tx_retjourneyplanner_form[search][date]' => date('y-m-d'),
                'tx_retjourneyplanner_form[search][time]' => ($time[$journeyIndex] === 'now') ? date('H:m') : $time[$journeyIndex],
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
    
    /**
     * @return array
     */
    public function getIndexes(){

        for ($j = 0; $j <= count($this->typeAndLines); $j++) {
            $randomNumbers[] = mt_rand(1, count($this->typeAndLines));
        }

        return array_values(array_unique($randomNumbers));
    }

    /**
     * @return void
     */
    public function closeCookieBar()
    {
        $this->clickOnClassOrId('.cookie-bar__close');
    }

}
