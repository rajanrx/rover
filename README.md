# rover [![Build Status](https://travis-ci.org/rajanrx/rover.svg?branch=master)](https://travis-ci.org/rajanrx/rover)

Mars Rover Code Challenge 

# usage

You need to have PHP 7.1 or above to run this project.

Either clone this Repo , install dependencies
```
git clone https://github.com/rajanrx/rover.git && composer install
```
and then run command

```
php command.php --input 'data/sample-input.txt' --output 'data/sample-output.txt'
```

# Test Coverage

To generate the report run the following command and you can access coverage html
```
./bin/phpunit --coverage-html ./report
```
![Test Coverage](https://github.com/rajanrx/rover/blob/master/data/test-coverage.png)