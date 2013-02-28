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
 * The payload handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Payload
 * @since 1.0.1
 */
abstract class Payload implements IPayload, IBuilder {
    
    /**
     * The payload data
     * @var array
     * @since 1.0.1
     */
    protected $payload;
    
    /**
     * Create a new Payload
     * @param array $payload The payload data
     * @since 1.0.1
     */
    public function __construct($payload){
        $this->payload = $payload;
    }
    
    /**
     * Load the payload from the input
     * @return \Deployer\Payload\Payload Returns the payload created
     * @throws \Exception
     * @since 1.0.0
     */
    public static function fromCurrent(){
        if(isset($_POST['payload']) && $_POST['payload']){
            $payload = new self(json_decode($_POST['payload'], true));
            return $payload;
        }
        throw new \Exception('Payload cannot be loaded from current HTTP request');
    }
    
}