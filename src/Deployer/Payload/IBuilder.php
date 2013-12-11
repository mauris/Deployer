<?php

/**
 * Deployer
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Deployer\Payload;

/**
 * Interface for a payload builder
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Payload
 * @since 1.0.1
 */
interface IBuilder
{
    /**
     * Load the payload from the input
     * @return \Deployer\Payload\Payload
     * @since 1.0.1
     */
    public static function fromCurrent();
}
