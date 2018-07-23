<?php
/**
 * Created by PhpStorm.
 * User: buivol
 * Date: 23.07.2018
 * Time: 11:05
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$config = require_once 'config.php';

$fInput = file_get_contents("php://input");
$json = json_decode($fInput, 1);
$event = $_SERVER['HTTP_X_GITHUB_EVENT'];

$date = date('dmY_His');
$logName = 'logs/' . $date . '_' . $event . '.json';

file_put_contents($logName, $fInput);

echo 'event: ' . $event;

echo PHP_EOL;

if ($event == 'push') {
    $branch = $json['ref'];
    if ($branch == 'refs/heads/develop') {
        // develop ветка
        echo 'Ветка: develop' . PHP_EOL;
        echo PHP_EOL;
        $generateFile .= '#!/usr/bin/env bash' . PHP_EOL;
        $generateFile .= 'echo "Generated file from PSF Updater Developer branch"' . PHP_EOL . 'whoami' . PHP_EOL;
        $generateFile .= 'cd ' . $config['develop']['path']['repo'] . PHP_EOL;
        $generateFile .= 'git reset --hard HEAD' . PHP_EOL;
        $generateFile .= 'git checkout develop' . PHP_EOL;
        $generateFile .= 'git pull' . PHP_EOL;
        $generateFile .= 'cd ' . $config['develop']['path']['public'] . PHP_EOL;
        $generateFile .= 'composer install --no-ansi --no-interaction --no-scripts --optimize-autoloader --no-progress' . PHP_EOL;
        $generateFile .= 'composer update  --working-dir=/var/www/dev/data/new/psf/public_html --no-ansi --no-interaction --no-scripts --no-progress --optimize-autoloader' . PHP_EOL;
        $generateFile .= 'cd ' . $config['develop']['path']['public'] . PHP_EOL;
        $generateFile .= 'php artisan migrate --force' . PHP_EOL;
        $generateFile .= 'echo "Finished"' . PHP_EOL;
        file_put_contents('composer-update-dev.sh', $generateFile);
        chmod('composer-update-dev.sh', '0777');

        $command = __DIR__ . '/composer-update-dev.sh > composer-output-dev.txt';
        $output = system($command);
        var_dump($output);
        echo $command;

    } else {

        echo 'Неизвестная ветка ' . $branch . PHP_EOL;
    }
}


//print_r($_SERVER); //shell_exec( 'cd /srv/www/git-repo/ && git reset --hard HEAD && git pull' );