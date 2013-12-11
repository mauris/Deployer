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

##Compiling

You can clone Deployer's repository and compile Deployer into PHAR easily by following the steps below:

Clone Deployer's repository:
   > $ git clone https://github.com/thephpdeveloper/Deployer.git  
   > $ cd Deployer

Install dependencies with [Composer](http://getcomposer.org):

   > $ curl -sS https://getcomposer.org/installer | php  
   > $ php composer.phar install

Download [Packfire Concrete](https://github.com/packfire/concrete) into the Deployer folder:
   > $ wget -q http://mauris.sg/bin/concrete.phar

Run Packfire Concrete:

   > $ php concrete.phar

You will find `deployer.phar` generated.

##Installation

 1. Download the [Deployer](http://mauris.sg/bin/deployer.phar) PHAR file.
 2. Create your Hook Script (see at `example/hook.php` in repository)
 3. Update your Hook Script to fetch either from Github or BitBucket in the `use` statement. 
 3. Upload both the `deployer.phar` and `hook.php` to your server. 
 4. Configure your repository by going to the settings on the Git hosting page and adding your hook call URL.
   - for example on Github, go to `Admin` tab on the repository page, select `Service Hooks` and click `WebHooks URLs` and add in your URL there.
 5. And you're done configuring - start pushing commits and see it deploying automatically!

##Configuration

The `Deployer` class accepts configuration to make Deployer cater to your needs.

    $deployer = Payload::fromCurrent()->load(array('target' => '../'));

In the example above, `load($config)` accepts configuration in the form of an associative array. The configuration can be as follows:

- **https** (boolean) - Sets whether to use https or not. Defaults to `true`.
- **target** (string) - Sets the target directory to deploy to.
- **autoDeploy** (boolean) - Sets if deploy is automated. If set to `true`, only commits with `[skipdeploy]` in the message will be skipped. If false, only commits with `[deploy]` in the message will be deployed. Defaults to `true`.
- **dateFormat** (string) - Sets the date date/time format in the logs. Format follows PHP `date()` format. 
- **logFile** (string) - Sets the pathname to the log file to write to. Set `null` to disable logging. Defaults to `deploy.log`.
- **branch** (string) - Sets the branch name to deploy from. Defaults to `master`. 
- **ipFilter** (array/string) - Sets the array of IP addresses to receive the payload from.

###Communication Protocols
Deployer currently supports pulling of repositories via HTTP and HTTPS only. By default, pulling will be done via HTTPS. If your repository is set to private, the username and password that has access to the repository MUST be supplied via the `$deployer->login($username, $password)` method in order for Git to be able to clone properly. Currently SSH and Git protocols are not supported (i.e. `git@` or `ssh://`).

###Logging
Logging of Deployer's operations is by default done by writing to `deploy.log`. For debugging purposes you may wish to look at the `deploy.log` file after calling the hook. You may turn off the logging by setting the `logFile` option to `false` or `null`.

###Skipping Commit Deployment

It is possible to skip deployment on certain commits or only deploy certain commits. The behaviour is set by the option `autoDeploy`.

 - If `autoDeploy` is set to **`true`**, all commits are deployed except for those commits with `[skipdeploy]` written in the commit message. 
 - If `autoDeploy` is set to **`false`**, only commits with the `[deploy]` written in the commit message will be deployed.

The keywords `[skipdeploy]` and `[deploy]` can be configured through the *constants* in the `Deployer` class.