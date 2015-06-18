# just-core
#just-core
##An Enterprise Grade PHP framework

........

[Quick Start Guide]
[Installation]
[Application Structure]



#Application Structure

 
 <br> <br> <br>

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
the foundation
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
