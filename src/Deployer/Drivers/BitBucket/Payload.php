<?php

/**
 * Deployer
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Deployer\Drivers\BitBucket;

use Deployer\Payload\Payload as BasePayload;
use Deployer\Payload\Commit;

/** 
 * The payload handler for BitBucket
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Drivers\BitBucket
 * @since 1.0.1
 */
class Payload extends BasePayload {
    
    public function commits() {
        $baseCommits = $this->payload['commits'];
        $commits = array();
        foreach($baseCommits as $commit){
            $commits[] = new Commit($commit['raw_node'], $commit['message']);
        }
        return $commits;
    }

    public function name() {
        return $this->payload['repository']['owner'] . '/' . $this->payload['repository']['slug'];
    }

}