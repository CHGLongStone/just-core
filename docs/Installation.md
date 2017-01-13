##1) Get a copy of JCORE
Check out a copy of JCORE from here: https://github.com/CHGLongStone/just-core.wiki.git
You can do this on a desktop for initial set up.

##2) Set up your source control layer
Just because JCORE uses GitHub by default doesn't mean that you have to yourself. You will need to move copies of some of the some of the directories in your own source control system.

####APIS
You can check out a local copy of this to see an example.

####PLUGINS
You can check out a local copy of this to see an example. Keep the same directory structure for your own plugins.

####TEMPLATES
You can check out a local copy of this to see an example. Keep the same directory structure for your own templates if you wish to use the native templating system.

####CONFIG
You need a local copy of this so your configurations are not over written by updates to CORE. Keep the same directory structure for your own plugins. it is worth consideration to keep a separate branch of config for DEV, UAT, PROD

####CACHE
Example of the cache directory, and the primary types requiring file I/O.
FILE
OPCODE
SESSION. 
This is defined in the API config.php file settings as required.

JCORE_FILE_CACHE_DIR
JCORE_OPCODE_CACHE_DIR
JCORE_SESSION_CACHE_DIR



##3) Configuration
#####DATA
######Copy:

    CONFIG/SERVICE/DATA/DATA.default.ini
    to 
    CONFIG/SERVICE/DATA/DATA.ini

and update your database configuration

#####CORE
######Edit: 
CONFIG/jcore.ini to set Application Date Formats and the environment (DEV/QA/PROD)

    DATE_FORMAT="Y-m-d"	;http://www.php.net/manual/en/function.date.php
    DATE_FORMAT_LONG="l jS \of F Y h:i:s A"  
    ENVIRONMENT="DEV" 

#####LOGGING
######Edit:
CONFIG/SERVICE/LOG/logServices.ini

    [JCORE]                        the logger name
    logFacility="FILE"             
    writePath="/var/log/"
    logName="JCORE_"
    dateFormat="Y-m-d H:i:s"
    logSuffix="log"
    stripWhitespace=TRUE
    bufferWrite=FALSE
    ;blockSize=[4096]
    


See: [Configure Logging] for more details



#####CACHE
######Edit:
CONFIG/SERVICE/CACHE_SOURCE.ini

    [CACHE_SOURCE]
    CSN[] = "BASIC_FILE_CACHE"
    CSN[] = "JCORE_SYSTEM"
    CSN[] = "JCORE_DATA"


See: [Configure Cache] for more details

##4) Set up an API
See: [APIS] for more details

##4) Load your plugins
See: [PLUGINS] for more details

##5) Test