<?php
require('deployer.phar');
use Deployer\Loader;

$loader = new Loader();

$loader->deploy('http://github.com/mauris/Deployer')->to('../deployer');
$loader->deploy('http://github.com/mauris/example')->to('../example')->with('username', 'User01')->with('password', 'p455w0rd');

$loader->load(Payload::fromCurrent());
