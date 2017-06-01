<?php
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Created by PhpStorm.
 * User: bart_
 * Date: 26-May-17
 * Time: 16:06
 */
class TestCase3Context extends MinkContext implements Context
{
    
    /**
     * @Then /^I try to create a new account$/
     */
    public function createAccount()
    {
        $this->getSession()->getPage();
    }
}