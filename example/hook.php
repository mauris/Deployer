<?php
include('src/Deployer/Bootstrap.php');
use Deployer\Drivers\Github\Deployer;
use Deployer\Drivers\Github\Payload;

if($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('payload', $_POST)){
    $source = json_decode($_POST['payload'], true);
    $deployer = new Deployer(Payload::fromCurrent(), array('target' => '../'));
    $deployer->login('username', 'password'); // normally only needed when repository is private
    $deployer->deploy();

    // write any code here to build the cloned files
}