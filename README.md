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
JCORE uses *.ini files for almost all configuration settings. basic settings are are in jcore.ini
the settings for CACHE_SOURCE, DATA and LOG are discussed in those subsections
pay particular attention to connections with MySQL when using multiple data sources

####JCORE/[CORE]
the foundation. Authentication/Authorization, Cache (opcode and data), Exception, Localization, LIB (3rd party tools), Load (bootstrap and autoload ) ,  Package utilities, Templater and Transport layer 
####JCORE/[PLUGINS]
default directory for packages and some example packages 
####JCORE/[PACKAGES]
default directory for packages and some example packages 
####JCORE/[TEMPLATES]
example templates for various file types CSS, HTML, Javascript, PHP and XML

####JCORE: [PACKAGES] vs [PLUGINS]

Given that JCORE is explicitly SOA plugins are meant to be discrete service objects/sets. Packages can be understood as collections of plugins to create an application or more complex API


[[project_screenshots]]
