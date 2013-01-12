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
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer
 * @since 1.0.0
 */
class Bootstrap {
    
    /**
     * Initializes the bootstrapping process
     * @since 1.0.0
     */
    public static function initialize(){
        set_include_path(dirname(__DIR__)
                . PATH_SEPARATOR . get_include_path());
        spl_autoload_register(function($class) {
            $class = ltrim($class, '\\');
            $fileName  = '';
            $namespace = '';
            $lastNsPos = strrpos($class, '\\');
            if($lastNsPos){
                $namespace = substr($class, 0, $lastNsPos);
                $class = substr($class, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
                        . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
            require_once($fileName);
        });
    }
    
}