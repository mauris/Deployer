<?php
namespace Deployer\Drivers;
use Deployer\Deployer as Deployer;

/**
 * Github Deployer class
 * 
 * A deployer from pulling data from Github
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Deployer.Drivers
 * @since 1.0
 */
class Github extends Deployer {
    
    public function __construct($data, $options = null) {
        $this->options['ipFilter'] = array(
            '207.97.227.253', '50.57.128.197', '108.171.174.178');
        parent::__construct($data, $options);
    }
    
    public function validate(){
        $this->log('Validation started');
        if(!$this->data){
            $this->validationError('Data Error: No data available.');
        }
        if(!$this->data['commits']){
            $this->validationError('Data Error: No commits in push hook.');
        }
        if(!$this->data['ref'] || !$this->data['before'] || !$this->data['after']){
            $this->validationError('Data Error: Ref, Before or after commit id not set.');
        }
        if(!$this->data['repository']){
            $this->validationError('Data Error: Repository information not set.');
        }
        if(!$this->data['repository']['owner'] || !$this->data['repository']['name']){
            $this->validationError('Data Error: Repository information incomplete; missing owner or name.');
        }
        $this->log('Validation successful');
    }
    
    public function buildUrl(){
        if($this->options['https']){
            $url = 'https://';
            if($this->username){
                $url .= $this->username;
                if($this->password){
                    $url .= ':' . $this->password;
                }
                $url .= '@';
            }
        }else{
            $url = 'http://';
        }
        $url .= 'github.com/' . $this->data['repository']['owner']['name'] . '/' . $this->data['repository']['name'] . '.git';
        return $url;
    }
    
    protected function findCommit(){
        $node = null;
        $commits = array_reverse($this->data['commits']);
        if($this->options['autoDeploy']){
            foreach($commits as $commit){
                if(strpos($commit['message'], self::HOOK_SKIP_KEY) === false){
                    $node = $commit['id'];
                    break;
                }
                $this->log('Skipping node "' . $commit['id'] . '".');
            }
        }else{
            foreach($commits as $commit){
                if(strpos($commit['message'], self::HOOK_DEPLOY_KEY) !== false){
                    $node = $commit['id'];
                    break;
                }
                $this->log('Skipping node "' . $commit['id'] . '".');
            }
        }
        return $node;
    }
    
}