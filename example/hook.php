<?php
require('deployer.phar');
use Deployer\Loader;

$loader = new Loader();

$loader->deploy('mauris/Deployer')->from('github')->to('../deployer');
$loader->deploy('mauris/example')->from('github')->to('../example')->with('username', 'User01')->with('password', 'p455w0rd');

$loader->load();
