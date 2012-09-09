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

 1. Download Deployer by clicking the [Github download](https://github.com/thephpdeveloper/Deployer/downloads).
 2. Extract the contents of the `src` folder in the zip file to a new `hook` folder on your web server root. *So assuming that your web server root is at `"/home/user/public_html/"`, you should extract the contents of the `src` folder to `"/home/user/public_html/hook/"`.*
 3. Copy the file `example/hook.php` to your `hook` folder.  *Your hook call URL will then, for example, be at `http://example.com/hook/hook.php`.*
 4. Open `hook.php` and modify:
   - `use Deployer\Drivers\Github as Worker;` - replace `Github` with the driver class you wish to use.
   - `$deployer = new Worker($source, array('target' => '../'));` - write any additional options in the 2nd parameters.
 5. If needed, write additional code / script to build your application after the `"// write any code here to build the cloned files"` comment.
 6. Configure your repository by going to the settings on the Git hosting page and adding your hook call URL.
   - for example on Github, go to `Admin` tab on the repository page, select `Service Hooks` and click `WebHooks URLs` and add in your URL there.
 7. And you're done configuring - start pushing commits and see it deploying automatically!

##Note

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

###Git Cloning
Whenever a hook call is received, Deployer clones the repository to a temporary directory, then performs a `checkout-index` to copy the files to your target directory. The files are overwritten and the temporary directory is removed. This may cause old file deposits i.e. files which were deleted from or moved elsewhere in the repository to stay.