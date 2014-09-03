<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer;

use Deployer\Payload\Factory;

class Loader
{
    private $definitions = array();

    public function deploy($repository)
    {
        $definition = new Definition($repository);
        $this->definitions[] = $definition;
        return $definition;
    }

    private function findDefinition($name, $source)
    {
        $source = strtolower($source);
        foreach ($this->definitions as $definition) {
            if ($definition->getRepository() == $name && $definition->getSource() == $source) {
                return $definition;
            }
        }
    }

    public function load($data = null)
    {
        if ($data) {
            $payload = new Factory($data);
        } else {
            $payload = Factory::fromCurrent()->create();
        }

        $definition = $this->findDefinition($payload->name(), get_class($payload));

        $deployer = $payload->load();
        $deployer->deploy();
    }
}
