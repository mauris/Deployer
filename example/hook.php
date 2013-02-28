<?php
include('src/Deployer/Bootstrap.php');
use Deployer\Drivers\Github\Payload;

$deployer = Payload::fromCurrent()->load(array('target' => '../'));
$deployer->login('username', 'password'); // normally only needed when repository is private
$deployer->deploy();