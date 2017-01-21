# Packages and Extensions


Packages in the just-core ecosystem are meant to be discrete extensions to the core platform. Package management is handled by [Composer](https://getcomposer.org/) and all public just-core packages are [registered](https://packagist.org/packages/just-core/) with [Packagist](https://packagist.org).

You can easily include other packages that are available through the [composer load mechanism](https://getcomposer.org/doc/05-repositories.md) including your private repositories, `these will require authentication`.




#### Directory Structure:

```
    [package name]/
        API/ 
            index.php 
        SERVICES/
            [service name].service.php
            [service name].entity.php
        composer.json
        CONFIG.AUTOLOAD.[name].[global/local].php
        [name].example.php
        README.md
```   

The Packages from just-core follow a specific structure according to the functionality they provide.
 * the `SERVICES` directory will almost always exist unless the package is specifically providing functionality for the transport/presentation layer only
 * the `API` directory will be supplied when the package requires an example API 
  - in this case the API path MUST be available in a context that allows the execution of PHP (HTTP, CLI)
   * it is expected this will be treated as a stub and copied into the main application repository to be modified
 * if there is a file with the mask CONFIG.AUTOLOAD.[name].[global/local/*].php 
  - it is expected this will be treated as a stub and copied into the main application repository under CONFIG/AUTOLOAD 
    * see [application-configuration](https://github.com/CHGLongStone/just-core-stub/wiki#application-configuration)
* if there is a file with the mask [name].example.php it will give an example of the package usage
* The packages namespace will be defined by the packages context within the just-core environment

 

The namespace call will be something like 
```
$LOGIN_SERVICE = new JCORE\SERVICE\AUTH\LOGIN_SERVICE;
```

## Quick Ref: 

### Things to know about Composer

#### Using external repositories [including private ones](https://getcomposer.org/doc/05-repositories.md#package-2)

```
	"repositories": [
		{
			"type": "package",
			"package": {
				"name": "just-core/scripts",
				"version": "master",
				"dist": {
					"type": "zip",
					"url": "https://github.com/CHGLongStone/just-core-scripts/archive/master.zip",
					"reference": "master"
				}
			}
		},
		{
			"type": "package",
			"package": {
				"name": "just-core/scripts",
				"version": "master",
				"source": {
					"type": "git",
					"url": "git@github.com:CHGLongStone/just-core-scripts.git",
					"reference": "master"
				}
			}
		}
	],
```

#### composer repository dist/source (downloader) types 

Available types: git, svn, fossil, hg, perforce, zip, rar, tar, gzip, xz, phar, file, path.

#### Running scripts
Multiple options for executing other services via scripts, like running [bower](https://bower.io/) for for front end dependencies. These require that the user executing the script and the php user (if defined) have read/execute permissions 
[more:](https://getcomposer.org/doc/articles/scripts.md)

```
        runs every installation
	"scripts": {
		"post-package-install": [
			"./check_scripts_install.sh;"
		]
	},


```

#### Autoload Directories

```
	"autoload" : {
		"classmap" : [
			"SERVICES",
			"3RD/PARTY/SERVICES",
			"OTHER/STUFF",
		]
	}
```
