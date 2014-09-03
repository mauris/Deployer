<?php /*
 * Copyright (C) 2013 Sam-Mauris Yong. All rights reserved.
 * This file is part of the asyncloader.js project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Deployer;

use Deployer\Payload\Payload;
use Symfony\Component\Process\Process;
use Packfire\Logger\File as Logger;
use Psr\Log\NullLogger;

/**
 * The deployer generic class that helps to pull Git repositories
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer
 * @since 1.0.0
 */
abstract class Deployer
{
    /**
     * The keyword for deploying the commit
     */
    const HOOK_DEPLOY_KEY = '[deploy]';

    /**
     * The keyword for skipping this commit
     */
    const HOOK_SKIP_KEY = '[skipdeploy]';

    protected $options = array(

        /**
         * Set whether to use HTTPS or not. If authentication is used,
         * this is overwritten to be true.
         */
        'https' => true,

        /**
         * The target directory to deploy
         */
        'target' => __DIR__,

        /**
         * Determine whether deploys will be automated.
         * if true, only commits with [skipdeploy] will be skipped, and the rest will be deployed.
         * if false, only commits with [deploy] will be deployed, and the rest will be skipped.
         */
        'autoDeploy' => true,

        /**
         * The log date time format
         * See http://www.php.net/manual/en/function.date.php for formatting syntax.
         */
        'dateFormat' => 'Y-m-j H:i:sO',

        /**
         * The deployment log file
         */
        'logFile' => 'deploy.log',

        /**
         * The default branch to fetch
         */
        'branch' => 'master',

        /**
         * An array of IPs that is valid for the deployment operation
         */
        'ipFilter' => null
    );

    /**
     * The username for HTTPS authentication
     * @var string
     * @since 1.0.0
     */
    protected $username;

    /**
     * The username for HTTPS authentication
     * @var string
     * @since 1.0.0
     */
    protected $password;

    /**
     * The payload
     * @var \Deployer\Payload\Payload
     * @since 1.0.1
     */
    protected $payload;

    /**
     * The logger that writes
     * @var \Psr\Log\LoggerInterface
     * @since 1.1.3
     */
    protected $logger;

    /**
     * Create a new Deployer object
     * @since 1.0.0
     */
    public function __construct(Payload $payload, $options = null)
    {
        set_error_handler(function ($errno, $errstr, $errfile = null, $errline = null) {
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        });
        $this->logger = new NullLogger();

        if (is_array($options)) {
            $this->loadOptions($options);
        }
        $this->payload = $payload;
    }

    /**
     * Update the options in Deployer
     * @param array $options
     * @since 2.0.0
     */
    protected function loadOptions($options)
    {
        foreach ($this->options as $key => &$value) {
            if (array_key_exists($key, $options)) {
                $value = $options[$key];
            }
        }

        // if there is a change in log file
        if (isset($options['logFile']) && $this->options['logFile']) {
            // if it is not an absolute path then we use the current working directory
            if (!preg_match('/^(?:\/|\\|[a-z]\:\\\).*$/i', $this->options['logFile'])) {
                $this->options['logFile'] = getcwd() . '/' . $this->options['logFile'];
            }

            $this->logger = new Logger($this->options['logFile']);
        }
    }

    /**
     * Enter the credentials for authentication
     * @param string $username The username
     * @param string $password The password
     * @since 1.0.0
     */
    public function login($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->options['https'] = true;
        $this->logger->info(sprintf('Signing in as "%s".', $username));
    }

    /**
     * Execute a shell command and perform logging
     * @param string $cmd The command to execute
     * @since 1.0.0
     */
    public function execute($cmd)
    {
        $this->logger->info(sprintf('Executing command: %s', $cmd));
        $process = new Process($cmd);
        if ($process->run() == 0) {
            $output = $process->getOutput();
        } else {
            throw new \RuntimeException('Failed to run command "' . $cmd . '". Output: ' . $process->getOutput());
        }
        if ($output) {
            $this->logger->info(sprintf("Output:\n%s", $output));
        }
    }

    /**
     * Performs IP filtering check
     * @throws Exception Thrown when requestor IP is not valid
     * @since 1.0.0
     */
    protected function ipFilter()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if ($this->options['ipFilter'] && !in_array($ipAddress, (array)$this->options['ipFilter'])) {
            throw new \Exception('Client IP not in valid range.');
        }
        $this->logger->info('IP Address ' . $ipAddress . ' filtered.');
    }

    /**
     * Build the URL to clone the git repository
     * @return string The URL returned
     * @since 1.0.0
     */
    abstract public function buildUrl();

    /**
     * Find the next commit to deploy based on the rules of [deploy] and [skipdeploy]
     * @return string Returns the commit to clone
     * @since 1.0.0
     */
    protected function findCommit()
    {
        $node = null;
        $commits = array_reverse($this->payload->commits());
        foreach ($commits as $commit) {
            /* @var $commit \Deployer\Payload\Commit */
            if ($this->options['autoDeploy'] && strpos($commit->message(), self::HOOK_SKIP_KEY) === false) {
                $node = $commit->commit();
                break;
            } else if (!$this->options['autoDeploy'] && strpos($commit->message(), self::HOOK_DEPLOY_KEY) !== false) {
                $node = $commit->commit();
                break;
            }
            $this->logger->info('Skipping node "' . $commit->commit() . '".');
        }
        return $node;
    }

    /**
     * Perform the deployment operations
     * @since 1.0.0
     */
    public function deploy()
    {
        $url = $this->buildUrl();
        $node = $this->findCommit();

        if ($url && $node) {
            $this->logger->info(sprintf('Commit "%s" will be checked out.', $node));
            $path = $this->options['target'];

            $currentDir = getcwd();

            ignore_user_abort("1");
            set_time_limit(0);

            if (!is_dir($path)) {
                $this->logger->info('Target directory not found, creating directory at ' . $path);
                mkdir($path);
            }
            chdir($path);
            try {
                $this->execute('git rev-parse');
            } catch (\Exception $e) {
                $this->logger->info('Repository not found. Cloning repository.');
                $this->execute('git init');
                $this->execute(sprintf('git remote add origin "%s"', $url));
            }
            $this->logger->info(sprintf('Checking out repository at %s', $node));
            $this->execute(sprintf('git pull origin %s', $this->options['branch']));
            $this->execute(sprintf('git checkout %s', $node));

            chdir($currentDir);
        } else {
            $this->logger->info('No node found to deploy.');
        }
        $this->logger->info('Deploy completed.');
    }
}
