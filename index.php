<?php
/**
 * Created by PhpStorm.
 * User: buivol
 * Date: 23.07.2018
 * Time: 11:05
 */


$fInput = file_get_contents("php://input");
$json = json_decode($fInput);
$event = $_SERVER['HTTP_X_GITHUB_EVENT'];

$date = date('dmY_His');
$logName = 'logs/' . $date . '_' . $event . '.json';

file_put_contents($logName, $fInput);

echo 'event: ' . $event;

echo PHP_EOL;

print_r($_SERVER);