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


require 'vendor/autoload.php';


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
        $command = 'whoami';
        $out = shell_exec($command);
        echo 'Выполнена команда: '. $command . PHP_EOL;
        echo 'Ответ: ' . PHP_EOL;
        var_dump($out);
        echo PHP_EOL;
        $command = 'cd ' . $config['develop']['path']['repo'] . ' && git reset --hard HEAD && git checkout develop && git pull';
        $out = shell_exec($command);
        echo 'Выполнена команда: '. $command . PHP_EOL;
        echo 'Ответ: ' . PHP_EOL;
        var_dump($out);
        echo PHP_EOL;


        $command = 'cd ' . $config['develop']['path']['public'] . ' && php composer.phar install --working-dir=' .$config['develop']['path']['public'] .' --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader && php composer.phar update --working-dir=' .$config['develop']['path']['public'] .' --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader';

        putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin/composer');
// Improve performance when the xdebug extension is enabled
        putenv('COMPOSER_DISABLE_XDEBUG_WARN=1');
// call `composer install` command programmatically
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        try {
            $params = array(
                'command' => 'install',
                '--no-dev' => true,
                '--optimize-autoloader' => true,
                '--no-suggest' => true,
                '--no-interaction' => true,
                '--no-progress' => true,
                '--working-dir' => $config['develop']['path']['public'],
                '--verbose' => true,
            );
            $input = new \Symfony\Component\Console\Input\ArrayInput($params);
            $application = new \Symfony\Component\Console\Application();
            $application->setAutoExit(false);
            $application->run($input, $output);
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
        }

        $stream_output = $output->fetch();// get buffer string
        echo $stream_output;

        //$out = shell_exec($command);
       // echo 'Выполнена команда: '. $command . PHP_EOL;
        //echo 'Ответ: ' . PHP_EOL;
        //var_dump($out);
        echo PHP_EOL;
        $command = 'cd ' . $config['develop']['path']['public'] . ' && php artisan migrate --force';
        $out = shell_exec($command);
        echo 'Выполнена команда: '. $command . PHP_EOL;
        echo 'Ответ: ' . PHP_EOL;
        var_dump($out);
        echo PHP_EOL;
    } else {

        echo 'Неизвестная ветка ' . $branch . PHP_EOL;
    }
}


//print_r($_SERVER); //shell_exec( 'cd /srv/www/git-repo/ && git reset --hard HEAD && git pull' );