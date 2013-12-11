<?php
require('deployer.phar');
use Deployer\Drivers\Github\Payload;

$deployer = Payload::fromCurrent()->load(array('target' => '../'));
$deployer->login('username', 'password'); // normally only needed when repository is private
$deployer->deploy();
