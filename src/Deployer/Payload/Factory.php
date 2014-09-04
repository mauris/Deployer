<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer\Payload;

/**
 * The payload factory
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Payload
 * @since 2.0.0
 */
class Factory implements FactoryInterface
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function create()
    {
        if (isset($this->payload['canon_url']) && $this->payload['canon_url'] == 'https://bitbucket.org') {
            return $this->createPayload('BitBucket');
        }
        if (isset($this->payload['repository']) && isset($this->payload['repository']['url']) && strpos($this->payload['repository']['url'], 'https://github.com/') !== false) {
            return $this->createPayload('Github');
        }
        throw new \Exception('Factory cannot be create payload.');
    }

    protected function createPayload($name)
    {
        $class = new ReflectionClass('Deployer\\Drivers\\' . $name . '\\Payload');
        $instance = $class->newInstanceArgs(array($this->payload));
        return $instance;
    }

    /**
     * Load the payload from the input
     * @return \Deployer\Payload\FactoryInterface Returns the payload factory
     * @throws \Exception
     * @since 2.0.0
     */
    public static function fromCurrent()
    {
        if (isset($_POST['payload']) && $_POST['payload']) {
            $payload = json_decode($_POST['payload'], true);
            return new Factory($payload);
        }
        throw new \Exception('Payload cannot be loaded from current HTTP request');
    }
}
