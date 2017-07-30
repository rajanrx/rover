<?php

use Rover\RoverManager;

require_once __DIR__ . '/vendor/autoload.php';

$roverManager = RoverManager::getInstance();
$roverManager->readInputFromFile(__DIR__ . '/data/input.txt');
$roverManager->moveRovers();
$roverManager->writeOutPutToFile('');