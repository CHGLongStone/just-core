# A PHP Framework for Enterprise Service Buses

just-core is a Service Oriented Architecture providing a modular and extensible Enterprise Service Bus foundation. 

This is a framework "foundation" that designed from hard lessons learned in both high load (100K++ concurrent) 
and highly volatile distributed environments but simple enough to use as a back end for basic AJAX driven sites and applications.

The primary objective is KISS (Keep It Simple Stupid) for ease of development, change management and "late" optimization.

Very "simple" choices in structure and interface have been made in core because they are more readily understood, 
reducing "ramp up" time and more importantly they are easy to modify and extend.

[test](#test)
[Reduced-Effort](#Reduced-Effort)

# Providing Things you need in any framework... 
- **Clear Architecture**
  - Core services with clearly defined interfaces 
- **Flexibility**
  - Reduced Bloat with _Service Modularity_ and _Lazy Loading_
  - Multiple Data store and Cache options
  - Easy to implement logging and change auditing 
- **Reduced effort** for
  - _New development_ 
    - Low Cost Of Entry on the learning curve 
    - Using common standards
    - Staying close to the native language 
    - providing common utilities without imposing a coding style on your work
  - _Optimization and performance tuning_
    - With easily extensible or replaceable Services or Service Objects 
  - _Change Management_
    - Clear separation of the framework from your own intellectual property
    - Simple configuration management for multiple environments
    - Configurable scripts to support the SLDC

# The Break down:

## Architectural Principles

**just-core was culled and refined from over a decade of practical experience in a number of distributed environments 
and developed with a number of driving influences:**

* **A Response to "Overhead" in other frameworks**
* **Pure self interest**
* **The Need to Support Enterprise/Service Oriented Architecture environments at _scale_**

### Response to "Overhead" in other frameworks 

Frustration with available frameworks in the language 

* **Marketing BS deeper than a java memory leak**
  * ascribing "magical" or non-required/non-existent properties like dependency injection to runtime languages/frameworks
  
* **trying to "do all, be all" for _every possible feature and function_**
 * **_NOT abstracted solutions_**
      * that increase overhead for IP specific solutions by requiring rewrite
 * **_OVER abstracted solutions_**
      * incurring **_BLOAT_** factor 
      * _undermining_ the ability to optimize or "performance tune" 
* **requiring the developer to learn a "new language"**
  *  by enforcing specific patterns through the entire application stack 
  
  
### Pure self interest

**To save effort and frustration _seek out the best_ in each work environment and _learn hard lessons early_:**

* **your opinions how it "should work" mean nothing until it runs a clean stack trace**
  * in a production environment
  * with a concurrency that forces load balancing
* **the biggest contrarian to your approach might be your greatest teacher** 
  * cover your blind spots
  * if you can't pass the internal criticism benchmark don't expect it to "survive the wild"
* **differentiate and learn from(save references to) good design patterns**
* **Keep control of YOUR Intellectual Property**
  * maintain the IP stack that is core to your business offering
  * keep it un-entangled from proprietary dependencies wherever possible

The project started as an attempt to pull a decade of lessons learned and a very fragmented personal tool kit into a coherent package 
to support personal as well as professional projects.

The project was migrated from sourceforge and subversion to github and git as a source control mechanism in 2014 as well as updated 
to address new standards in the language (PSR name-spaces) as well as new tools for 

- a Name-space/lazy loading mechanism 
- package / plugin management 

which had been "standardized" with [composer](https://getcomposer.org) and [packagist](https://packagist.org) providing both public 
and private distribution channels.


### The Need to Support Enterprise/Service Oriented Architecture environments at _scale_

Providing the Foundation Framework (minimum core services buses) required for any genuine enterprise level application at scale (_minimum_ 100K concurrent connections)

* boot-strapping servers or applications with configuration management for multiple environments
* Clear and consistent interfaces for core services 
  * Authentication/Authorization support
  * Data Store Access
  * Caching 
  * Agnosticism towards the transport layer
  * logging and auditing 
* Ability to easily extend or replace any given service class/api/layer


##### Service Oriented Architecture: the just-core service buses

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





# Business by Design

just-core is designed around the Enterprise Service Bus and Enterprise Architecture Requirements but is grounded 
in the Pareto Principle, "80% of the effects come from 20% of the causes" from both business and practical approaches.

##  Business: Focus on the 20% 

In many case your business is probably operating in a crowded market space and despite the delusions of grandeur of your CEO or VP of Sales  
the differentiation of your product offering in all probability, realistically offers far less than a 20% variance of functionality to your competitors.
If your company has accumulated a large degree of technical debt through your development phase you will have great difficulty achieving scale if you are 
lucky enough to require it.

Ensure your company is able to develop it's market differentiation by ensuring the practical "housekeeping" is part of process from the ground up

## Practical: Cover the 80% so you can focus on the 20% 

The bottom line is that most business ignore "general housekeeping" leading to "technical debt" that frequently ends up consuming 80% of the resources 
required to operate the business because of poor or ineffective policies or procedures around change management in the forms of:

* lack of coherent architecture
  * confusing and inconsistent implementations 
  * lack of institutional knowledge around core systems
* lack of basic performance optimization 
 * succumbing to the "throw hardware at it" method of application tuning and increasing op-ex


**Reduce the overhead through clear and common standards and mechanisms providing:**

 * clear separation between the transport mechanism and the business logic
 * data and cache store interactions
 * logging for performance motoring
 * auditing services for:
   * business intelligence KPIs
   * regulatory or contractual compliance 
		  
		
**So you can focus on the innovation and intellectual property development that is _driving_ your business**
 




## Flexibility

### Name Space loading

Dependency Management via Composer/Packagist and PSR-4 namespaces, Lazy Loading of service calls by namespace 
through multiple transport types from AJAX to XML. 


### Cache and Data Store

Multiple Cache and Data store options with extensible Data Access Objects and Data Store Connectors for rapid development without 
compromising late optimization



#Reduced-Effort

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
* [Quick Start Guide](QuickStart)
* [Installation](https://github.com/CHGLongStone/just-core-stub/wiki/Project-Installation)
* [Framework-Structure](https://github.com/CHGLongStone/just-core/wiki/Framework-Structure)
* [Packages](https://github.com/CHGLongStone/just-core/wiki/Packages-(extensions))



The project has developed under the TOGAF Architectural Development Model* 
* see www.opengroup.org/togaf/ for more information on The Open Group Architectural Framework and the TOGAF Architectural Development Model

licensed under: http://www.opensource.org/licenses/osl-3.0
plugins and packages may use what ever license they wish. JCORE [just-core] is designed to separate the foundation layer from the business logic and implementation