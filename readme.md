#Deployer

A simple Git web hook and deployment activator for continuous integration.

Basically what Deployer does is simple:

 1. Interpret the data sent by the various Git vendors (i.e. Github, BitBucket) via Web Service / Hook calls
 2. Clone the repository according to the data (you can determine which commit to skip or deploy in your commit message)
 3. Copy your clone to the target directory for build

You no longer have to deploy web applications via remote SSH or FTP anymore. Simply put this script onto your web-server with Git installed and add the web service URL to your project settings.

A sample web service / hook script is included as sampleHook.php.

##License

Deployer is released open source under the [New BSD License](https://github.com/thephpdeveloper/Deployer/blob/master/LICENSE).