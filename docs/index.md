# A PHP Framework for Enterprise Service Buses

just-core is a Service Oriented Architecture providing a modular and extensible Enterprise Service Bus foundation. 

This is a framework "foundation" that designed from hard lessons learned in both high load (100K++ concurrent) 
and highly volatile distributed environments but simple enough to use as a back end for basic AJAX driven sites and applications.

The primary objective is KISS (Keep It Simple Stupid) for ease of development, change management and "late" optimization.

Very "simple" choices in structure and interface have been made in core because they are more readily understood, 
reducing "ramp up" time and more importantly they are easy to modify and extend.


# Providing Things you need in any framework... 
- Clear Architecture
  - Core services with clearly defined interfaces 
- Flexibility 
  - Service Modularity + Lazy Loading = No Bloat
  - Multiple Cache and Data store options
  - Easy to implement logging and change auditing 
- Reduced effort for
  - New development 
    - Low Cost Of Entry on the learning curve 
    - Using common standards
    - Staying close to the native language 
    - providing common utilities without imposing a coding style on your work
  - Optimization and performance tuning 
    - Extensible or replaceable Service Objects 
  - Change Management 
    - Clear separation of the framework from your own intellectual property
    - Simple configuration management for multiple environments
    - Configurable scripts to support the SLDC


	 
	 
	 
# The Break down:

## Architecture

### [](#header-6)Header 6

### just-core service buses

 - [Initialization](https://github.com/CHGLongStone/just-core/wiki/Load)
   - Configuration Mangement
   - Application bootstrap and "lazy loading" of service classes
 - [Auth API](https://github.com/CHGLongStone/just-core/wiki/AUTH)
   - Harness for an Authentication/Authorization API 
 - [Caching API](https://github.com/CHGLongStone/just-core/wiki/Cache) 
    - API for:
      - multiple cache types -opcode, data - read or write through, http, etc. 
      - with multiple caching options -file, memcached, NoSQL, xcache, etc.
 - [Data API](https://github.com/CHGLongStone/just-core/wiki/Data-layer) 
   - Connection management and CRUD interace to multiple data store types
     - SQL - Standard RDBMS types like MySQL and PostgreSQL
     - NoSQL - Redis and other document based data stores 
     - file
  - [Data Access Objects](https://github.com/CHGLongStone/just-core/wiki/DAO)
    - Basic and extesnsible 
    - "Scheama Aware" without the bloat of Object Relational Management
 - [Transport](https://github.com/CHGLongStone/just-core/wiki/Transport)
   - Clear Separation of the transport layer from business logic
   - Send/Recieve JSON-RPC, ReST, XML requests/responses to the same service classes
 - [Log](https://github.com/CHGLongStone/just-core/wiki/Log)
   - log at varried thresholds to multiple targets (DB, File, UDP) 
   - [Exception Management](https://github.com/CHGLongStone/just-core/wiki/Exception)
   - [Localization](https://github.com/CHGLongStone/just-core/wiki/Localization) 
  - later implementation but not an afterthought
    - expected support for: 
      - older standards like `*.po` files
      - newer standards like DITA, TMX 
 - [Templater]()
   - it's basic, it's there...but why at this point in time, render html server side?



## Flexibility

### Name Space loading

Dependency Management via Composer/Packagist and PSR-4 namespaces, Lazy Loading of service calls by namespace 
through multiple transport types from AJAX to XML. 


### Cache and Data Store

Multiple Cache and Data store options with extensible Data Access Objects and Data Store Connectors for rapid development without 
compromising late optimization



## Reduced Effort

### Simplified Development 

Add a service class, update composer autoconfig and call it by namespace 


### Optimization

A bias towards performance tuning with low framework overhead, complete an http request with a complete stack trace of 10K+ internal function calls 
rather than waiting for 7K+ internal calls before you even load your service classes...like some other frameworks

Application profiling via [xhprof](https://github.com/phacility/xhprof) or using [XDEBUG](https://xdebug.org/) for complete stack traces 
__dependent on your development environment__

### Change Management

#### Simple configuration management

Simple configuration management for multiple environments from `dev/uat/prod` to dev sandboxes with Zend style  `*{global,local}.php`  configuration file naming patterns.

Clear separation of the framework from your own intellectual property

#### Business Intelligence and System oversight

Easy to implement logging and change auditing 

 

#### Scripts to support the SLDC

Configurable scripts to support the Software Development Life Cycle including:

- Installing/updating the project
- Release Tag Generation for git repositories with pre-validation checking:
  - Commonly used project directories for changes
  - Your own included packages for changes
  - Database Schema Changes between Upstream and Downstream environments
  - Upstream and Downstream primary and composite/dependant repository changes 
- Before creating the release tag as well as supporting scripts for
  - deploying releases to your production environment
  - database backup and syncronization MySQL inc. InnoDb supported 


















## Quick menu

* [WIKI](https://github.com/CHGLongStone/just-core/wiki)
* [Quick Start Guide](https://github.com/CHGLongStone/just-core/wiki/QuickStart)
* [Installation](https://github.com/CHGLongStone/just-core-stub/wiki/Project-Installation)
* [Framework-Structure](https://github.com/CHGLongStone/just-core/wiki/Framework-Structure)
* [Packages](https://github.com/CHGLongStone/just-core/wiki/Packages-(extensions))



The project has developed under the TOGAF Architectural Development Model* 
* see www.opengroup.org/togaf/ for more information on The Open Group Architectural Framework and the TOGAF Architectural Development Model

licensed under: http://www.opensource.org/licenses/osl-3.0
plugins and packages may use what ever license they wish. JCORE [just-core] is designed to separate the foundation layer from the business logic and implementation