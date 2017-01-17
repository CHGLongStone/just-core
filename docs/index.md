# A PHP Framework for Enterprise Service Buses

just-core is a Service Oriented Architecture providing a modular and extensible Enterprise Service Bus foundation. 

This is a framework "foundation" that designed from hard lessons learned in both high load (100K++ concurrent) 
and highly volatile distributed environments but simple enough to use as a back end for basic AJAX driven sites and applications.

The primary objective is KISS (Keep It Simple Stupid) for ease of development, change management and "late" optimization.

Very "simple" choices in structure and interface have been made in core because they are more readily understood, 
reducing "ramp up" time and more importantly they are easy to modify and extend.


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


# Business by Design

##### just-core is designed around the Enterprise Service Bus and Enterprise Architecture Requirements and is grounded in the Pareto Principle, "80% of the effects come from 20% of the causes" from both business and practical approaches.

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


# Flexibility Through:

### Name Space loading

Dependency Management via Composer/Packagist and PSR-4 namespaces, Lazy Loading of service calls by namespace 
through multiple transport types from AJAX to XML. 


### Extensible Cache and Data Store options

Multiple Cache and Data store options with extensible Data Access Objects and Data Store Connectors for rapid development without 
compromising late optimization



#Reduced-Effort with:

### Simplified Development 

Add a service class, update composer autoconfig and call it by namespace 


### Architectural Emphasis on Late Optimization

A bias towards performance tuning with low framework overhead, complete an http request with a complete stack trace of 10K+ internal function calls 
rather than waiting for 7K+ internal calls before you even load your service classes...like some other frameworks

Application profiling via [xhprof](https://github.com/phacility/xhprof) or using [XDEBUG](https://xdebug.org/) for complete stack traces 
__dependent on your development environment__

### Change Management

##### Simple configuration management

Simple configuration management for multiple environments from `dev/uat/prod` to dev sandboxes with Zend style  `*{global,local}.php`  configuration file naming patterns.

Clear separation of the framework from your own intellectual property

### Business Intelligence and System oversight from the start

Easy to implement logging, application/performance monitoring and change auditing 

 

### Scripts to support the SLDC

Configurable scripts to support the Software Development Life Cycle including:

- Installing/updating the project
- Release Tag Generation for git repositories with pre-validation checking:
  - Commonly used project directories for changes
  - Your own included packages for changes
  - Database Schema Changes between Upstream and Downstream environments
  - Upstream and Downstream primary and composite/dependant repository changes 
- Before creating the release tag as well as supporting scripts for
  - deploying releases to your production environment
  - database backup and synchronization MySQL inc. InnoDb supported 






 
## Service Oriented Architecture: 

Read more about the projects [Architectural Principles](Architecture) or explore the service bus below


## The just-core service buses:
 - [Initialization](Load)
   - Configuration Management
   - Application bootstrap and "lazy loading" of service classes
 - [Auth API](AUTH)
   - Harness for an Authentication/Authorization API 
 - [Caching API](Cache) 
    - multiple cache types -opcode, data - read or write through, http, etc. 
    - with multiple caching options -file, memcached, NoSQL, xcache, etc.
 - [Data API](Data-layer) 
   - Connection management 
   - CRUD interface to multiple data store types
     - SQL - Standard RDBMS types like MySQL and PostgreSQL
     - NoSQL - Redis and other document based data stores 
     - file
 - [Data Access Objects](DAO)
    - Basic and extensible 
    - "Schema Aware" without the bloat of Object Relational Management
 - [Transport](Transport)
   - Clear Separation of the transport layer from business logic
   - Send/Receive JSON-RPC, ReST, XML requests/responses to the same service classes
 - [Log](Log)
   - log at varied thresholds to multiple targets (DB, File, UDP) 
 - [Exception Management](Exception)
 - [Localization](Localization) 
   - later implementation but not an afterthought
     - expected support for: 
       - older standards like `*.po` files
       - newer standards like DITA, TMX 
 - [Templater](Templater)
   - it's basic, it's there...but why at this point in time, render html server side?



   
   
   

The project has developed under the TOGAF Architectural Development Model* 
* see www.opengroup.org/togaf/ for more information on The Open Group Architectural Framework and the TOGAF Architectural Development Model

licensed under: http://www.opensource.org/licenses/osl-3.0
plugins and packages may use what ever license they wish. JCORE [just-core] is designed to separate the foundation layer from the business logic and implementation