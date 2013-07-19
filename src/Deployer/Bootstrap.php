<?php

/**
 * Deployer
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Deployer;

/**
 * Bootstrapper
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer
 * @since 1.0.0
 */
class Bootstrap
{
    private static function acquire($file)
    {
        if (is_file($file)) {
            return include($file);
        }
    }

    public static function run()
    {
        if (!($loader = self::acquire(__DIR__ . '/../../vendor/autoload.php'))) {
            echo 'You must set up project\'s dependencies first by running the following commands:' . PHP_EOL;
            echo "    curl -s https://getcomposer.org/installer | php\n";
            echo "    php composer.phar install\n";
            exit(1);
        }
        return $loader;
    }
}

return Bootstrap::run();
