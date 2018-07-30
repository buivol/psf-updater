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



$publicPath = $config[$branch]['path']['repo']. DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR;
$storagePath = $publicPath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR;

$result['publicPath'] = $publicPath;
$result['storagePath'] = $storagePath;

//$optimizerChain->optimize($pathToImage);

// optimize storage/app/uploads/public

$opt1path = $storagePath . 'app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
$files = dirToArray($opt1path);

dd($files);

echo json_encode($result);