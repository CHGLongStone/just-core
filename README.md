# just-core

## A PHP Enterprise Service Bus framework

### Providing:
* Things you need in any framework 
 - core services with clearly defined interfaces 
 - No BLOAT by giving you modular control to include the things you need and ignore the ones you don't
 - low cost of entry on the learning curve 
  * providing common utilities without imposing a coding style on you by:
   - Staying close to the native language 
   - using common standards like:
    * Dependency Management via Composer/Packagist and PSR-4 namespaces
    * Simple configuration management for multiple environments from dev/uat/prod to dev sandboxes with
     - `*{global,local}.php` "Zend style" configuration file naming pattern
 - low cost of effort for: 
  * new development - add a service class, update composer autoconfig and call it by namespace
  * change management 
   - clear separation of the framework from your own intelectual property
   - configurable scripts to support the SLDC including:
    * installing/updating the project
    * release tag generation for git repositories with pre-validation checking:
     - commonly used project directories for changes
     - your own included packages for changes
     - database schema changes between upstream and downstream environments
     - upstream and downstream primary and compostie/dependant repository changes 
    * before creating the tag as well as supporting scripts for:
     - deploying releases to your production environment
     - database backup and syncronization (MySQL inc InnoDb) 
* to support enterprise level applications 
 - Service Oriented Architecture supporting a modular and extensible core Enterprise Service Bus
 - Name Space loading of service classes by multiple transport methods
 - Multiple Cache and Data store options
 - Extensible Data Access Objects and Data Store Connectors for rapid development without compromising "late optimization" 
 - Easy to implement logging and change auditing 
 - A bias towards performance tuning with: 
  * low framework overhead, complete an http request with a complete stack trace of 10K(+) internal function calls 
   - rather than waiting for 7K+ internal calls before you even load your service classes...like some other frameworks
  * Application profiling via [xhprof](https://github.com/phacility/xhprof)
   -  Using [XDEBUG](https://xdebug.org/) for complete stack traces is dependent on your development environment



### Qick menu
* [Quick Start Guide](https://github.com/CHGLongStone/just-core/wiki/QuickStart)
* [Installation](https://github.com/CHGLongStone/just-core-stub/wiki/Project-Installation)
* [Framework-Structure](https://github.com/CHGLongStone/just-core/wiki/Framework-Structure)



# Architecture

## just-core service busses
 * [Initialization](https://github.com/CHGLongStone/just-core/wiki/Load)
  - Configuration Mangement
  - Application bootstrap and "lazy loading" of service classes
 * [Auth API](https://github.com/CHGLongStone/just-core/wiki/AUTH)
  - Harness for an Authentication/Authorization API 
 * [Caching API](https://github.com/CHGLongStone/just-core/wiki/Cache) 
  - API for:
   * multiple cache types 
    - opcode, data (read or write through), http...
   * with multiple caching options
    - file, memcached, NoSQL, xcache, etc.
 * [Data API](https://github.com/CHGLongStone/just-core/wiki/Data-layer) 
  - Connection management and CRUD interace to multiple data store types
   * SQL (Standard RDBMS types like MySQL and PostgreSQL)
   * NoSQL (Redis and other document based data stores )
   * file
  - [Data Access Objects](https://github.com/CHGLongStone/just-core/wiki/DAO)
   * Basic and extesnsible 
   * "Scheama Aware" without the bloat of Object Relational Management
 * [Transport](https://github.com/CHGLongStone/just-core/wiki/Transport)
  - Clear Separation of the transport layer from business logic
  - Send/Recieve JSON-RPC, ReST, XML requests/responses to the same service classes
 * [Log](https://github.com/CHGLongStone/just-core/wiki/Log)
  - log at varried thresholds to multiple targets (DB, File, UDP) 
 * [Exception Management](https://github.com/CHGLongStone/just-core/wiki/Exception)
 * [Localization](https://github.com/CHGLongStone/just-core/wiki/Localization) 
  - later implementation but not an afterthought
   * expected support for: 
    - older standards like `*.po` files
    - newer standards like DITA, TMX 
 * [Templater]()
  - it's basic, it's there...but why at this point in time, render html server side?

 
the foundation [just-core service bus](https://github.com/CHGLongStone/just-core/wiki/just-core-service-bus)







#### Configuration Management
just-core uses *.php files for almost all configuration settings in a similar manner to Zend Framework configurations. 

files use the name spaces

* error.php - an example file
* error.global.php - a global file (across repository installs)
* error.local.php - a file local to the particular instance
 
These are defined in the project using the framework, see [just-core-stub](https://github.com/CHGLongStone/just-core-stub) for an example.






####JCORE/[PACKAGES]
default directory for packages and some example packages 





[[project_screenshots]]
