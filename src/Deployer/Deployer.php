<?php

namespace Deployer;

/**
 * Deployer class
 * 
 * The deployer generic class that helps to pull Git repositories
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer
 * @since 1.0
 */
abstract class Deployer{
    
    /**
     * The keyword for deploying the commit
     */
    const HOOK_DEPLOY_KEY = '[deploy]';
    
    /**
     * The keyword for skipping this commit
     */
    const HOOK_SKIP_KEY = '[skipdeploy]';
    
    const LOG_INFO = 'info';
    
    const LOG_WARNING = 'warn';
    
    const LOG_ERROR = 'error';
    
    const LOG_DEBUG = 'debug';
    
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
        
    );
    
    /**
     * The username for HTTPS authentication
     * @var string
     * @since 1.0
     */
    protected $username;
    
    /**
     * The username for HTTPS authentication
     * @var string
     * @since 1.0
     */
    protected $password;
    
    /**
     * The data received from the hook call
     * @var array
     * @since 1.0
     */
    protected $data;
    
    /**
     * Create a new Deployer object
     * @since 1.0
     */
    public function __construct($data, $options = null){
        $obj = $this;
        set_error_handler(function($errno, $errstr, $errfile = null, $errline = null, $errcontext = null )use($obj){
            $obj->log($errstr, Deployer::LOG_ERROR);
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        });
        
        $this->data = $data;
        if(is_array($options)){
            $this->options($options);
        }else{
            $this->log('Deployer started with default options.');
        }
        
    }
    
    /**
     * Update the options in Deployer
     * @param array $options
     * @since 1.0
     */
    public function options($options){
        foreach($this->options as $key => &$value){
            if(array_key_exists($key, $options)){
                $value = $options[$key];
            }
        }
        $this->log('Options updated:' . json_encode($this->options));
    }
    
    /**
     * Enter the credentials for authentication
     * @param string $username The username
     * @param string $password The password
     * @since 1.0
     */
    public function login($username, $password){
        $this->username = $username;
        $this->password = $password;
        $this->options['https'] = true;
        $this->log(sprintf('Signing in as "%s".', $username));
    }
    
    /**
     * Performs the validation of the received information
     * @since 1.0
     */
    public function validate(){
        
    }
    
    /**
     * Log a validation error
     * @param string $message The error message
     * @throws \Exception
     * @since 1.0
     */
    protected function validationError($message){
        $this->log($message, self::LOG_ERROR);
        throw new \Exception($message);
    }
    
    /**
     * Write to the log file
     * @param string $message The message to the log file
     * @param string $type (optional) The log type
     * @since 1.0
     */
    public function log($message, $type = self::LOG_INFO){
        $file = $this->options['logFile'];
        if($file){
            $fp = fopen($file, 'a');
            fwrite($fp, sprintf(
                    "%s [%s]: %s\n",
                    date($this->options['dateFormat']),
                    $type,
                    $message
                ));
            fclose($fp);
            chmod($file, 0666);
        }
    }

    /**
     * Execute a shell command and perform logging
     * @param string $cmd The command to execute
     * @since 1.0
     */
    public function execute($cmd){
        $this->log(sprintf('Executing command: %s', $cmd));
        $output = shell_exec($cmd);
        if($output){
            $this->log(sprintf("Output:\n%s", $output));
        }
    }
    
    /**
     * Recursively destroy a directory
     * @param string $dir The directory to destroy
     * @return boolean Tells if successful or not.
     * @since 1.0
     */
    protected static function destroyDir($dir){
        if(!file_exists($dir)){
            return false;
        }
        if (!is_dir($dir) || is_link($dir)){
            return unlink($dir); 
        }
        foreach (scandir($dir) as $file) { 
            if ($file == '.' || $file == '..') continue; 
            self::destroyDir($dir . DIRECTORY_SEPARATOR . $file);
        } 
        return rmdir($dir); 
    }
    
    /**
     * Recursively change the permissions of files and folders
     * @param string $dir The path to set new permissions
     * @param integer $mode The permissions to set
     * @since 1.0
     */
    protected static function chmodR($dir, $mode){
        if(!file_exists($dir)){
            return;
        }
        if (is_dir($dir) && !is_link($dir)){
            foreach (scandir($dir) as $file) { 
                if ($file == '.' || $file == '..') continue; 
                self::chmodR($dir . DIRECTORY_SEPARATOR . $file, $mode);
            }
        }
        chmod($dir, $mode);
    }
    
    /**
     * Build the URL to clone the git repository
     * @return string The URL returned
     * @since 1.0
     */
    public abstract function buildUrl();
    
    /**
     * Find the next commit to deploy based on the rules of [deploy] and [skipdeploy]
     * @return string Returns the commit to clone
     * @since 1.0
     */
    protected abstract function findCommit();
    
    /**
     * Perform the deployment operations
     * @since 1.0
     */
    public function deploy(){
        $url = $this->buildUrl();
        $node = $this->findCommit();
        
        if($url && $node){
            $this->log(sprintf('Commit "%s" will be checked out.', $node));
            $target = $this->options['target'];
            
            $this->log('Creating temporary directory...');
            if(is_dir('temp')){
                $this->log('Temp directory already exist. '
                        . 'Removing temp directory first...');
                self::chmodR('temp', 0777);
                self::destroyDir('temp');
            }
            mkdir('temp');
            
            ignore_user_abort(true);
            set_time_limit(0);
            $this->log('Fetching from Git repository');
            $this->execute('cd temp && git init');
            $this->execute(sprintf('cd temp && git remote add origin %s', $url));
            $this->execute(sprintf(
                    'cd temp && git pull origin %s', $this->options['branch']
                ));
            $this->execute(sprintf('cd temp && git checkout %s', $node));
            $this->execute('cd temp && git reset --hard HEAD');
            
            $this->log('Preparing target directory...');
            if(!is_dir($target)){
                $this->log('Target directory does not exist. '
                        . 'Creating target directory first...');
                mkdir($target);
            }
            $path = realpath($this->options['target']);
            if($path == ''){
                $path = __DIR__;
            }
            $path .= DIRECTORY_SEPARATOR;
            $this->log(sprintf('Deploying to %s...', $path));
            
            $this->execute(sprintf(
                    'cd temp && git checkout-index -af --prefix=%s',
                    $path
                ));
            
            $this->log('Removing temp directory...');
            self::chmodR('temp', 0777);
            self::destroyDir('temp');
        }else{
            $this->log('No node found to deploy.');
        }
        $this->log('Deploy completed.');
    }
    
}