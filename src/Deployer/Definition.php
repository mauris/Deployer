<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer;

class Definition
{
    private $repository;

    private $options = array();

    private $target;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function to($target)
    {
        $this->target = $target;
    }

    public function with($key, $value)
    {
        $this->options[$key] = $value;
    }
}
