<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer\Drivers\Github;

use Deployer\Payload\Payload as BasePayload;
use Deployer\Payload\Commit;

/**
 * The payload handler for Github
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Drivers\Github
 * @since 1.0.1
 */
class Payload extends BasePayload
{
    public function commits()
    {
        $baseCommits = $this->payload['commits'];
        $commits = array();
        foreach ($baseCommits as $commit) {
            $commits[] = new Commit($commit['id'], $commit['message']);
        }
        return $commits;
    }

    public function name()
    {
        return $this->payload['repository']['owner']['name'] . '/' . $this->payload['repository']['name'];
    }
    
    public function load($config = array())
    {
        return new Deployer($this, $config);
    }
}
