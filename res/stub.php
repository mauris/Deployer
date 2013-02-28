#!/usr/bin/env php
<?php

/**
 * Deployer
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

Phar::mapPhar('deployer.phar');
require 'phar://deployer.phar/src/Deployer/Bootstrap.php';

__HALT_COMPILER();