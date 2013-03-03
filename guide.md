#Deployer Setup Guide

This document will guide you to set up Deployer on your own server to allow hook calls from git hostings to achieve continuous integration.

##Requirements
Deployer requires the following:

 - **PHP 5.3 or higher**
   - well, Deployer's written in PHP.
 - **Git installed locally**
   - for fetching the repository
 - **Web server**
   -  the hook page needs to be facing the web to receive the hook calls from the git hostings

##Git Hosting Support

Deployer supports web service hook calls from the following git hosting service providers:

 - [Github](https://github.com/)
 - [BitBucket](https://bitbucket.org/)

##Installation

 1. Download the [Deployer](http://mauris.sg/bin/deployer.phar) PHAR file.
 2. Create your Hook Script (see at `example/hook.php` in repository)
 3. Upload both the `deployer.phar` and `hook.php` to your server. 
 4. Configure your repository by going to the settings on the Git hosting page and adding your hook call URL.
   - for example on Github, go to `Admin` tab on the repository page, select `Service Hooks` and click `WebHooks URLs` and add in your URL there.
 5. And you're done configuring - start pushing commits and see it deploying automatically!

###Deployment Target

You are able to set where to deploy the downloaded files from by setting the `target` option. Both relative and absolute paths are welcome.

###Communication Protocols
Deployer currently supports pulling of repositories via HTTP and HTTPS only. By default, pulling will be done via HTTPS. If your repository is set to private, the username and password that has access to the repository MUST be supplied via the `Deployer::login($username, $password)` method in order for Git to be able to clone properly. Currently direct Git protocol is not supported (i.e. `git://`).

###Logging
Logging of Deployer's operations is by default done by writing to `deploy.log`. For debugging purposes you may wish to look at the `deploy.log` file after calling the hook. You may turn off the logging by setting the `logFile` option to `false` or `null`.

###Skipping Commit Deployment

It is possible to skip deployment on certain commits or only deploy certain commits. The behaviour is set by the option `autoDeploy`.

 - If `autoDeploy` is set to **`true`**, all commits are deployed except for those commits with `[skipdeploy]` written in the commit message. 
 - If `autoDeploy` is set to **`false`**, only commits with the `[deploy]` written in the commit message will be deployed.

The keywords `[skipdeploy]` and `[deploy]` can be configured through the *constants* in the `Deployer` class.