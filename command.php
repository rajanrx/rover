<?php

use Rover\RoverManager;

require_once __DIR__ . '/vendor/autoload.php';


$command = new Commando\Command();
// Option for input
$command->option('i')->aka('input')->require()->describedAs('Input File');
// Option for output
$command->option('o')->aka('output')->require()->describedAs(
    'Output File'
);

// Process Rover instructions
try {
    $roverManager = RoverManager::getInstance();
    $roverManager->readInputFromFile($command['input']);
    $roverManager->moveRovers();
    $roverManager->writeOutPutToFile($command['output']);
} catch (\Exception $ex) {
    $command->error($ex);
    echo PHP_EOL;
    exit(1);
}

echo PHP_EOL;
exit(0);