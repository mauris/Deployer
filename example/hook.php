<?php
include('src/Deployer/Bootstrap.php');
Deployer\Bootstrap::initialize();
use Deployer\Drivers\Github\Deployer as Worker;

if($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('payload', $_POST)){
    $source = json_decode($_POST['payload'], true);
    $deployer = new Worker($source, array('target' => '../'));
    $deployer->login('username', 'password'); // normally only needed when repository is private
    $deployer->validate();
    $deployer->deploy();

    // write any code here to build the cloned files
}