<?php
/**
 * Created by PhpStorm.
 * User: buivol
 * Date: 07.08.2018
 * Time: 11:47
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$fInput = file_get_contents("php://input");
$json = json_decode($fInput, 1);
$event = $_SERVER['HTTP_X_GITHUB_EVENT'];

$date = date('dmY_His');
$logName = 'logs/' . $date . '_' . $event . '_avito.json';


file_put_contents($logName, $fInput);

echo 'event: ' . $event;

echo PHP_EOL;
print_r(getenv('HOME'));
print_r(getenv('COMPOSER_HOME'));
echo PHP_EOL;
echo 'putenv';
putenv('COMPOSER_HOME=/var/www/dev/data/.config/composer');
putenv('HOME=/var/www/dev/data');
echo PHP_EOL;
print_r(getenv('HOME'));
print_r(getenv('COMPOSER_HOME'));
echo PHP_EOL;

print_r(getenv('HOME'));
print_r(getenv('COMPOSER_HOME'));
if ($event == 'push') {
    echo 'Ветка: master' . PHP_EOL;
    $logNameD = 'logs/deploy_master_' . $date . '.log';
    $command = 'cd /var/www/dev/data/avito && dep deploy -vvv --branch=master --no-interaction --log=' . __DIR__ . '/' . $logNameD;
    $output = shell_exec($command);
    var_dump($output);
    echo $command;
}


//print_r($_SERVER); //shell_exec( 'cd /srv/www/git-repo/ && git reset --hard HEAD && git pull' );