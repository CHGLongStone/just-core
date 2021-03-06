# just-core

### Quick menu
* [Documentation](https://chglongstone.github.io/just-core/)
* [WIKI](https://chglongstone.github.io/just-core/wiki)
* [Quick Start Guide](QuickStart)
* [Installation](/Project-Installation)
* [Framework-Structure](https://chglongstone.github.io/just-core/wiki/Framework-Structure)
* [Packages](https://chglongstone.github.io/just-core/wiki/Packages-(extensions))


## A PHP Enterprise Service Bus framework

### Providing:
- Things you need in any framework 
 -  Core services with clearly defined interfaces 
 -  No BLOAT by giving you modular control to include the things you need and ignore the ones you do not
 -  Low Cost Of Entry on the learning curve by
   -   providing common utilities without imposing a coding style on you 
    -   Staying close to the native language and using common standards like
      -    Dependency Management via Composer/Packagist and PSR-4 namespaces
      -    Simple configuration management for multiple environments from `dev/uat/prod` to dev sandboxes 
        -       with `*{global,local}.php` Zend style configuration file naming patterns
 -  Low level of effort for
   -  New development - add a service class, update composer autoconfig and call it by namespace
    -  Change Management 
    -  Clear separation of the framework from your own intelectual property
    -  Configurable scripts to support the SLDC including:
     -   Installing/updating the project
      -   Release Tag Generation for git repositories with pre-validation checking:
       -  Commonly used project directories for changes
       -  Your own included packages for changes
        -   Database Schema Changes between Upstream and Downstream environments
        -   Upstream and Downstream primary and composite/dependant repository changes 
      -   Deployment with supporting scripts for
       -   deploying releases to your multiple environments
       -   database backup and syncronization for MySQL (InnoDb supported) Postgres...
* To support Enterprise Applications 
 - Service Oriented Architecture supporting a modular and extensible core Enterprise Service Bus
  -  Name Space loading of service classes by multiple transport methods
  -  Multiple Cache and Data store options
  -  Extensible Data Access Objects and Data Store Connectors for 
  -   rapid development without compromising late optimization
  -   Easy to implement logging and change auditing 
  -  A bias towards performance tuning with
    -   low framework overhead, complete an http request with a complete stack trace of 10K+ internal function calls 
      -   rather than waiting for 7K+ internal calls before you even load your service classes...like some other frameworks
    -   Application profiling via [xhprof](https://github.com/phacility/xhprof)
     -   Using [XDEBUG](https://xdebug.org/) for complete stack traces is dependent on your development environment





# Architecture

## just-core service buses
 * [Initialization](https://chglongstone.github.io/just-core/wiki/Load)
  - Configuration Mangement
  - Application bootstrap and "lazy loading" of service classes
 * [Auth API](https://chglongstone.github.io/just-core/wiki/AUTH)
  - Harness for an Authentication/Authorization API 
 * [Caching API](https://chglongstone.github.io/just-core/wiki/Cache) 
  - API for:
    * multiple cache types -opcode, data - read or write through, http, etc. 
    * with multiple caching options -file, memcached, NoSQL, xcache, etc.
 * [Data API](https://chglongstone.github.io/just-core/wiki/Data-layer) 
  - Connection management and CRUD interace to multiple data store types
    * SQL - Standard RDBMS types like MySQL and PostgreSQL
    * NoSQL - Redis and other document based data stores 
    * file
  - [Data Access Objects](https://chglongstone.github.io/just-core/wiki/DAO)
    * Basic and extesnsible 
    * "Schema Aware" without the bloat of Object Relational Management
 * [Transport](https://chglongstone.github.io/just-core/wiki/Transport)
  - Clear Separation of the transport layer from business logic
  - Send/Recieve JSON-RPC, ReST, XML requests/responses to the same service classes
 * [Log](https://chglongstone.github.io/just-core/wiki/Log)
  - log at varried thresholds to multiple targets (DB, File, UDP) 
 * [Exception Management](https://chglongstone.github.io/just-core/wiki/Exception)
 * [Localization](https://chglongstone.github.io/just-core/wiki/Localization) 
  - later implementation but not an afterthought
   * expected support for: 
     - older standards like `*.po` files
     - newer standards like DITA, TMX 
 * [Templater]()
  - it's basic, it's there...but why at this point in time, render html server side?

 
