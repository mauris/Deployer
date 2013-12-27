<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer\Drivers\Github;

/**
 * Test class for Deployer.
 * Generated by PHPUnit on 2012-09-09 at 07:43:57.
 */
class DeployerTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateSuccess()
    {
        $source = json_decode(file_get_contents('test/sampleGoodGithubData.json'), true);
        $object = new Deployer(new Payload($source), array('logFile' => null));
        $this->assertEquals('https://github.com/defunkt/github.git', $object->buildUrl());
    }
}
