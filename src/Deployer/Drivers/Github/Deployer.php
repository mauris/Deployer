<?php

/**
 * Deployer
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Deployer\Drivers\Github;

use Deployer\Payload\Payload as BasePayload;
use Deployer\Deployer as BaseDeployer;

/**
 * A deployer from pulling data from Github
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Drivers\Github
 * @since 1.0.0
 */
class Deployer extends BaseDeployer {
    
    public function __construct(BasePayload $payload, $options = null) {
        $this->options['ipFilter'] = array(
            '207.97.227.253', '50.57.128.197', '108.171.174.178'
        );
        parent::__construct($payload, $options);
    }
    
    public function buildUrl(){
        if($this->options['https']){
            $url = 'https://';
            if($this->username){
                $url .= $this->username;
                if($this->password){
                    $url .= ':' . $this->password;
                }
                $url .= '@';
            }
        }else{
            $url = 'http://';
        }
        $url .= 'github.com/' . $this->payload->name() . '.git';
        return $url;
    }
    
}