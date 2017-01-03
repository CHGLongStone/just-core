# just-core

##A PHP Enterprise Service Bus framework

........

* [Quick Start Guide](https://github.com/CHGLongStone/just-core/wiki/QuickStart)
* [Installation](https://github.com/CHGLongStone/just-core-stub/wiki/Project-Installation)
* [Framework-Structure](https://github.com/CHGLongStone/just-core/wiki/Framework-Structure)



#Application Structure


###The Layout
the expected install is expected to use composer https://getcomposer.org/




####JCORE/[CONFIG]
JCORE uses *.php files for almost all configuration settings in a similar manner to Zend Framework configurations. 

files use the name spaces

* error.php - an example file
* error.global.php - a global file (across repository installs)
* error.local.php - a file local to the particular instance
 
These are defined in the project using the framework, see [just-core-stub](https://github.com/CHGLongStone/just-core-stub) for an example.


####JCORE/[CORE]
the foundation [just-core service bus](https://github.com/CHGLongStone/just-core/wiki/just-core-service-bus)
* Authentication/Authorization
* Cache (opcode and data)
* DAO (Data Access Objects)
* Exception
* Localization
* Load (bootstrap and autoload )
* Log
* Templater
* Transport layer 


####JCORE/[PLUGINS]
default directory for packages and some example packages 
####JCORE/[PACKAGES]
default directory for packages and some example packages 
####JCORE/[TEMPLATES]
example templates for various file types CSS, HTML, Javascript, PHP and XML

####JCORE: [PACKAGES] vs [PLUGINS]

Given that JCORE is explicitly SOA plugins are meant to be discrete service objects/sets. Packages can be understood as collections of plugins to create an application or more complex API


[[project_screenshots]]