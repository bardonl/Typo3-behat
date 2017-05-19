<?php
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Class TestCase1Context, houses all of the custom context for the first testcase.
 * Author: Bart de Geus <degeus@redkiwi.nl>, Redkiwi
 */
class TestCase1Context extends MinkContext implements Context
{
    use FeatureContext;
    use HelperContext;
    
    /**
     * @param string $time
     *
     * @Then /^I check time of lines with time "([^']*)" and all days of the week$/
     */
    public function checkTime($time)
    {
        $this->timer(1);

        if ($this->assertSession()->elementExists('css', '.cookie-bar') && $this->assertSession()->elementExists('css', '.is-visible')) {
            
            $this->closeCookieBar();
        }
       
        $indexArray = $this->getIndexes();
        
        for ($i = 0; $i < count($indexArray); $i++) {

            $linesClasses = $this->typeAndLines[$indexArray[$i]];
    
            $line = $this->getLines($linesClasses);
    
            print('Checking line ' . $line . '...' . PHP_EOL);
           
            $this->clickOnClassOrId($linesClasses);

            if (!strstr($linesClasses, '-bobbus-')) {
    
                print('Checking roundtrip of ' . $line . '...' . PHP_EOL);
                
                $this->clickOnClassOrId('.tooltip--ellipsis');
                $roundTrip = 'terug.html';

            } else {
                
                $roundTrip = 'heen.html';
            }
            
            if (is_array($line) && array_key_exists('error', $line) && $line['error'] === 1) {
                
                print($line['message'] . PHP_EOL);
            } else {
    
                print('Checking time (' . $time . ') of ' . $line . '...' . PHP_EOL);
                
                $this->visit('/home/reizen/dienstregeling/' . $line . '/' . date('Y-m-d') . 'T' . $time . '%2B02%3A00/' . $roundTrip);
                
                $days = $this->changeDay();
                
                for ($d = 0; $d < 7; $d++) {
                    $days[$d] = date('Y-m-d', strtotime('+1 day', strtotime($days[$d])));
    
                    print('Checking date (' . $days[$d] . ') of ' . $line . '...' . PHP_EOL);
                    
                    $this->visit('/home/reizen/dienstregeling/' . $line . '/' . $days[$d] . 'T' . $time . '%2B02%3A00/' . $roundTrip);
                }
            }
            
            $this->visit('/');
        }
    }
    
    /**
     * @param string $linesClass
     * @return array|string
     */
    public function getLines($linesClass)
    {

        switch ($linesClass) {
            case strstr($linesClass, '-boat-ferry') === '-boat-ferry':

                return 'fast-ferry';

            case strstr($linesClass, '-bobbus-') === '-bobbus-' . substr(strrchr($linesClass, '-'), 1):

                return 'bob-bus-' . substr(strrchr($linesClass, '-'), 1);

            case strstr($linesClass, '-bus-') === '-bus-' . substr(strrchr($linesClass, '-'), 1):

                return substr(strstr($linesClass, '-bus-'), 1);

            case strstr($linesClass, '-tram-') === '-tram-' . substr(strrchr($linesClass, '-'), 1):

                return substr(strstr($linesClass, '-tram-'), 1);

            default:

                return [
                    'message' => 'No line selected!',
                    'error' => 1
                ];
        }
    }
    
    
    /**
     * @return array
     */
    public function changeDay()
    {
        $timestamp = strtotime('next Sunday');
        
        for ($i = 0; $i < 7; $i++) {
            
            $days[] = strftime('%A', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }
        
        return $days;
    }
    
    /**
     * @Then /^I click on folder$/
     */
    public function createLineFolder()
    {
        if ($this->assertSession()->elementExists('css', '.cookie-bar') && $this->assertSession()->elementExists('css', '.is-visible')) {
            
            $this->closeCookieBar();
        }
    
        $this->timer(2);
        
        $script = <<<JS
        (function(){
            return $('.busline__buttons > .modal__toggle').css('transform', 'translateX(-100px)');
        })();
JS;
        
        $this->getSession()->executeScript($script);
        
        $this->assertSession()->elementExists('css', '.busline__buttons .modal__toggle')->click();
        
        $this->timer(1);
        
    }
    
    /**
     * @Then /^I choose different stops$/
     */
    public function chooseStops()
    {
        
        for ($i = 1; $i < 4; $i++) {
    
            $customOptions = $this->getSession()->getPage()->findAll("css", 'fieldset > div:nth-child(' . $i . ') > .timetable__bus-stop__title > div > label > span > .custom-select__options > .custom-select__option');
    
            $counter = 0;
            
            foreach($customOptions as $customOption){
                
                if (!empty($customOption)) {
                    
                    $counter +=1;
                }
            }
            
            $randomStop = rand(1, count($customOptions));
            
            $this->assertSession()->elementExists('css', 'fieldset > div:nth-child(' . $i . ') > .timetable__bus-stop__title > div > label > span > .custom-select__active')->click();
            
            $option = $this->assertSession()->elementExists('css', 'fieldset > div:nth-child(' . $i . ') > .timetable__bus-stop__title > div > label > span > .custom-select__options > span:nth-child(' . $randomStop . ')');
            
            $this->timer(1);
            
            $option->click();
            
            $this->timer(1);
        }
        
        $this->assertSession()->elementExists('css', '.modal__content > form > .form__input > button')->click();
    }
}