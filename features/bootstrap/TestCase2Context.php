<?php
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class TestCase2Context extends MinkContext implements Context
{
    use FeatureContext;
    use HelperContext;

}
