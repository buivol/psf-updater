<?php
/**
 * Created by PhpStorm.
 * User: buivol
 * Date: 23.07.2018
 * Time: 11:05
 */


$fInput = file_get_contents("php://input");
$json = json_decode($fInput);
$event = $_SERVER['X-GitHub-Event'];

$date = date('H:i:s_d.m.Y');
$logName = $date . '_' . $event . '.json';

file_put_contents($logName, $fInput);

echo 'hello ' . $event;