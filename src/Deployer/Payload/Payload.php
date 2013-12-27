<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
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
abstract class Payload implements IPayload, IBuilder
{
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
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Load the payload from the input
     * @return \Deployer\Payload\Payload Returns the payload created
     * @throws \Exception
     * @since 1.0.0
     */
    public static function fromCurrent()
    {
        if (isset($_POST['payload']) && $_POST['payload']) {
            $payload = new static(json_decode($_POST['payload'], true));
            return $payload;
        }
        throw new \Exception('Payload cannot be loaded from current HTTP request');
    }
}
