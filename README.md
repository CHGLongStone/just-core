# just-core
#just-core
##An Enterprise Grade PHP framework

See <a href="https://sourceforge.net/p/just-core/home/Home/">Home</a> for a summary.

[Quick Start Guide]
[Installation]
[Application Structure]



#Application Structure

 
 <br> <br> <br>

###The Layout
the expected install is something along the lines of
/var/www/VHOSTS/*hostname*/ - your "servable" directory root
/var/www/VHOSTS/*hostname*/[API*] - your API directories
/var/www/JCORE - your base install directory
/var/www/JCORE/CORE/ - a checkout of the JCORE project
/var/www/JCORE/CACHE/ - a writable cache directory
/var/www/JCORE/CONFIG/ - contains ini files for CORE
/var/www/JCORE/PLUGINS/ - plugin directory




But these can be any directory your application server has r/w access to. The subdirectories under JCORE are mapped as constants in the file JCORE/APIS/\[api_name\]/config.php

		define ("JCORE_BASE_DIR", "/var/www/JCORE/CORE/");
		define ("JCORE_CONFIG_DIR", "/var/www/JCORE/CONFIG/");
		define ("JCORE_PACKAGES_DIR", "/var/www/JCORE/PACKAGES/");
		define ("JCORE_TEMPLATES_DIR", "/var/www/JCORE/TEMPLATES/");
		define ("JCORE_LOG_DIR", "/var/log/httpd/"); 
		define ("JCORE_PLUGINS_DIR", "/var/www/JCORE/PLUGINS/");
		define ("JCORE_PACKAGES_DIR", "/var/www/JCORE/PACKAGES/");

 <br> <br> <br>
####JCORE/[APIS]
contains example API's (SOA, ReST, basic HTTP) as well as the "default_admin_api" 
if you want to expose this you can create a symlink under the http directory.
the API directory itself can contain as little as the index file and the config file. 

All of the business logic should be contained in the PLUGINS and PACKAGES.


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
