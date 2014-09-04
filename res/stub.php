#!/usr/bin/env php
<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

Phar::mapPhar('deployer.phar');
require 'phar://deployer.phar/src/Deployer/Bootstrap.php';

__halt_compiler();
