<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
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
class Deployer extends BaseDeployer
{
    public function __construct(BasePayload $payload, $options = null)
    {
        $this->options['ipFilter'] = array(
            '207.97.227.253',
            '50.57.128.197',
            '108.171.174.178'
        );
        parent::__construct($payload, $options);
    }

    public function buildUrl()
    {
        if ($this->options['https']) {
            $url = 'https://';
            if ($this->username) {
                $url .= urlencode($this->username);
                if ($this->password) {
                    $url .= ':' . urlencode($this->password);
                }
                $url .= '@';
            }
        } else {
            $url = 'http://';
        }
        $url .= 'github.com/' . $this->payload->name() . '.git';
        return $url;
    }
}
