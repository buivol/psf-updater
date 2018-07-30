<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$config = require_once 'config.php';

require_once __DIR__ . '/vendor/autoload.php';

$branch = isset($_GET['b']) ? $_GET['b'] : 'develop';
if (!in_array($branch, ['develop', 'master'])) $branch = 'develop';

$result = [
    'process' => 'storageOptimize',
    'branch' => $branch,
];

function dirToArray($dir)
{

    $result = array();

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $result[] = $value;
            }
        }
    }

    return $result;
}


use Spatie\ImageOptimizer\OptimizerChainFactory;

$optimizerChain = OptimizerChainFactory::create();
//storage/app/uploads/public

// optimize storage/app/uploads/public

$publicPath = $config[$branch]['path']['repo'];

$result['publicPath'] = $publicPath;

//$optimizerChain->optimize($pathToImage);


echo json_encode($result);