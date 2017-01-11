# just-core

## A PHP Enterprise Service Bus framework

### Providing:
* Service Oriented Architecture supporting a modular and extensible core Enterprise Service Bus
* Simple configuration management for multiple environments
* Dependency Management via Composer/Packagist and PSR-4 namespaces
* Support for rapid development without compromising optimization 

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
 * [Exception Management](https://github.com/CHGLongStone/just-core/wiki/Exception)
 * [Localization](https://github.com/CHGLongStone/just-core/wiki/Localization) 
  - later implementation but not an afterthought
   * expected support for: 
    - older standards like `*.po` files
    - newer standards like DITA, TMX 
 * [Templater]()
  - it's basic, it's there...but why render html server side?

 
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
