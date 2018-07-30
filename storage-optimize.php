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

echo json_encode($result);