<?php
include('src/Deployer/Bootstrap.php');
use Deployer\Drivers\Github\Deployer;
use Deployer\Drivers\Github\Payload;

$deployer = new Deployer(Payload::fromCurrent(), array('target' => '../'));
$deployer->login('username', 'password'); // normally only needed when repository is private
$deployer->deploy();