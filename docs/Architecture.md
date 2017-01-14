# Architecture

## just-core service buses
 * [Initialization](https://github.com/CHGLongStone/just-core/wiki/Load)
  - Configuration Mangement
  - Application bootstrap and "lazy loading" of service classes
 * [Auth API](https://github.com/CHGLongStone/just-core/wiki/AUTH)
  - Harness for an Authentication/Authorization API 
 * [Caching API](https://github.com/CHGLongStone/just-core/wiki/Cache) 
  - API for:
    * multiple cache types -opcode, data - read or write through, http, etc. 
    * with multiple caching options -file, memcached, NoSQL, xcache, etc.
 * [Data API](https://github.com/CHGLongStone/just-core/wiki/Data-layer) 
  - Connection management and CRUD interace to multiple data store types
    * SQL - Standard RDBMS types like MySQL and PostgreSQL
    * NoSQL - Redis and other document based data stores 
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

 