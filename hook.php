<?php
include('src/Deployer/Bootstrap.php');
Deployer\Bootstrap::initialize();
use Deployer\Drivers\BitBucket\Deployer as Worker;

$source = json_decode(file_get_contents('deploy.json'), true);
$deployer = new Worker($source, array('target' => 'C:\\test-repo'));
$deployer->validate();
$deployer->deploy();