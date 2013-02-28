<?php
include('src/Deployer/Bootstrap.php');
use Deployer\Payload\Factory;

$deployer = Factory::fromCurrent()->load(array('target' => '../'));
$deployer->login('username', 'password'); // normally only needed when repository is private
$deployer->deploy();