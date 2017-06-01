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
     * @Then /^I check time of lines with time "([^']*)" and check all the days of the week$/
     */
    public function checkTime($time)
    {
        $this->timer(1);
    
        if ($this->assertSession()->elementExists('css', '.cookie-bar') && $this->assertSession()->elementExists('css',
                '.is-visible')
        ) {
        
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
            
            }
        
            if (is_array($line) && array_key_exists('error', $line) && $line['error'] === 1) {
            
                print($line['message'] . PHP_EOL);
            } else {
    
                print('Checking time (' . $time . ') of ' . $line . '...' . PHP_EOL);
    
                $scriptResult = $this->jsClick('.input--update-timetable', $time);
    
                $this->getSession()->executeScript($scriptResult);
    
                $this->timer(2);
    
    
                $this->clickOnClassOrId('.favorite__date > .toggle');
    
                $this->timer(2);
    
                $days = $this->changeDay();
    
                for ($d = 0; $d < 7; $d++) {
                    $days[$d] = date('Y-m-d', strtotime('+1 day', strtotime($days[$d])));
        
                    print('Checking date (' . $days[$d] . ') of ' . $line . '...' . PHP_EOL);
        
                    $element = $this->getSession()->getPage()->find('css', 'a[data-date="' . $days[$d] . '"]');
        
                    $element->click();
        
                    $this->clickOnClassOrId('.favorite__date > .toggle');
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
        
        if (strstr($linesClass, '-boat-ferry') === '-boat-ferry') {

            return 'fast-ferry';
        }
        
        if (strstr($linesClass, '-bobbus-') === '-bobbus-' . substr(strrchr($linesClass, '-'), 1)) {

            return 'bob-bus-' . substr(strrchr($linesClass, '-'), 1);
        }

        if (strstr($linesClass, '-bus-') === '-bus-' . substr(strrchr($linesClass, '-'), 1)) {

            return substr(strstr($linesClass, '-bus-'), 1);
        }
        
        if (strstr($linesClass, '-tram-') === '-tram-' . substr(strrchr($linesClass, '-'), 1)) {

            return substr(strstr($linesClass, '-tram-'), 1);
        }
    }
    
    
    /**
     * @return array
     */
    public function changeDay()
    {
        $timestamp = strtotime('now');
        $days = [];
        
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
            return $('.busline__buttons > .modal__toggle').css('transform', 'translateX(-110px)');
        })();
JS;
        
        $this->getSession()->executeScript($script);
        
        $this->assertSession()->elementExists('css', '.busline__buttons .modal__toggle')->click();
        
        $this->timer(1);
        
    }
    
    /**
     * @Then /^I choose different stops and download the folder$/
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
            
            if (count($customOptions) !== 0) {
                $option = $this->assertSession()->elementExists('css', 'fieldset > div:nth-child(' . $i . ') > .timetable__bus-stop__title > div > label > span > .custom-select__options > span:nth-child(' . $randomStop . ')');
    
                $this->timer(1);
    
                $option->click();
            } else {
    
                $this->assertSession()->elementExists('css', 'fieldset > div:nth-child(' . $i . ') > .timetable__bus-stop__title > div > label > span > .custom-select__active')->click();
            }
            
            $this->timer(1);
        }
        
        $this->assertSession()->elementExists('css', '.modal__content > form > .form__input > button')->click();
    
        $this->timer(6);
        
    }
    
    /**
     * @Then /^I check if can print the timetable and is displayed correctly of line "([^']*)"$/
     */
    public function checkPrint($line)
    {
    
        $this->visit('/home/reizen/dienstregeling/' . $line . '.html');
        
        $script = <<<JS
        (function(){
            return $('.print').css('transform', 'translateX(-110px)');
        })();
JS;
    
        $this->getSession()->executeScript($script);
        
        $this->timer(1);
    
        $this->assertSession()->elementExists('css', '.print')->click();
    
        $this->timer(6);
    }
    
    /**
     * @param string $selector
     * @param string $value
     * @return string
     */
    public function jsClick($selector, $value)
    {
        return <<<JS
                (function(){
                   
                    var value = '$value';
                    var selector = '$selector';
                    
                    $(selector).click(function(){
                        $(this).val(value).trigger('blur');
                    }).click();
                    
                })();
JS;
    }
}