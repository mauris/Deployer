<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer\Payload;

/**
 * A payload commit information
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer\Payload
 * @since 1.0.1
 */
class Commit
{

    /**
     * The commit ID
     * @var string
     * @since 1.0.1
     */
    private $commit;

    /**
     * The commit message
     * @var string
     * @since 1.0.1
     */
    private $message;

    /**
     * Create a new Commit
     * @param string $commit The commit ID of this commit
     * @param string $message The commit message
     * @since 1.0.1
     */
    public function __construct($commit, $message)
    {
        $this->commit = $commit;
        $this->message = $message;
    }

    /**
     * Get the commit ID
     * @return string Returns the commit ID
     * @since 1.0.1
     */
    public function commit()
    {
        return $this->commit;
    }

    /**
     * Get the commit message
     * @return string Returns the commit message
     * @since 1.0.1
     */
    public function message()
    {
        return $this->message;
    }
}
