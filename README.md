# Behat #

### Introduction ###
Behat is an open source Behavior Driven Development framework for PHP 5.3+.


### Setup ###
* Get PhantomJS using brew,  `brew install phantomjs`
* Run `composer require`
* Run start.sh
* Now run `vendor/bin/behat` from the project root and it'll start testing.

You can also run the tests without PhantomJS but the javascript tests will fail.

### Hacks needed ###
This is for now a temporary hack until I can find out the actual source of the problem: 

<<<<<<< HEAD
Edit the file located in: `vendor/jcalderonzumba/gastonjs/src/Client/main.js` and remove `=== false` from the very last line.



=======
Edit the file located in: `vendor/jcalderonzumba/gastonjs/src/Client/main.js` and remove `=== false` from the very last line.
>>>>>>> master
