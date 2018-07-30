<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$config = require_once 'config.php';

require_once __DIR__ . '/vendor/autoload.php';

use Spatie\ImageOptimizer\OptimizerChainFactory;

$branch = isset($_GET['b']) ? $_GET['b'] : 'develop';
if (!in_array($branch, ['develop', 'master'])) $branch = 'develop';

$result = [
    'process' => 'storageOptimize',
    'branch' => $branch,
];

function dirToArray($dir)
{

    $result = [];
    if (!file_exists($dir)) return $result;
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, [".", ".."])) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $result[] = $value;
            }
        }
    }
    return $result;
}


function optimizeDir($path, $recursive = true, $optimizerChain = null)
{
    if (!$optimizerChain) {
        $optimizerChain = OptimizerChainFactory::create();
    }
    $result = [];
    $files = dirToArray($path);
    if (!count($files)) return $result;
    foreach ($files as $p => $file) {
        if ($recursive && is_array($file)) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $p;
            $result[$p] = optimizeDir($fullPath, $recursive, $optimizerChain);
        } else if (!is_array($file)) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $file;
            $a = explode('.', $file);
            $ext = strtolower(end($a));
            $fs = filesize($fullPath);
            $rsl = [
                'oldSize' => $fs,
                'newSize' => $fs,
                'optimized' => false,
                'optimizePlugin' => 'skip',
                'extension' => $ext,
            ];

            if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'gif'])) {
                $rsl['optimized'] = true;
                $rsl['optimizePlugin'] = 'spatie';
                $optimizerChain->optimize($fullPath);
                $rsl['newSize'] = filesize($fullPath);
            }

            $result[$file] = $rsl;
        } else {
            $result[$p] = [];
        }
    }
    return $result;
}

//storage/app/uploads/public


$publicPath = $config[$branch]['path']['repo'] . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR;
$storagePath = $publicPath . 'storage' . DIRECTORY_SEPARATOR;

$result['publicPath'] = $publicPath;
$result['storagePath'] = $storagePath;


// optimize storage/app/uploads/public

$opt1path = $storagePath . 'app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'public';
$files = optimizeDir($opt1path, 1);

$result['items'] = $files;

function scanFileSize($arr)
{
    $result = [
        'count' => 0,
        'size' => 0,
    ];
    foreach ($arr as $item) {
        if (!count($item)) continue;
        if (isset($item['optimized'])) {
            if ($item['optimized']) {
                $result['count']++;
                $result['size'] += $item['oldSize'] - $item['newSize'];
            }
        } else {
            $r = scanFileSize($item);
            $result['count'] += $r['count'];
            $result['size'] += $r['size'];
        }
    }
    return $result;
}

$scan = scanFileSize($files);

$result['count'] = $scan['count'];
$result['size'] = $scan['size'];

if(isset($_GET['dd'])){
    dd($result);
}

echo json_encode($result);