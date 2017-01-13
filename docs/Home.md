just-core
================================================================================

#just-core 

This is a framework "foundation" that designed from hard lessons learned in both high load (100K++ concurrent) and highly volatile distributed environments. 

As of June 2012 this project will proceed under the TOGAF ADM* and an Architectural Review Board will be established by Sept. 2012. Artifacts produced from the ADM will be included as part of the documentation of this project.

The primary objective is KISS (Keep It Simple Stupid) for ease of development, change management and "late" optimization.

Very "simple" choices in structure and interface have been made in core because they are more readily understood, reducing "ramp up" time and more importantly easy to modify and extend.

The namespace is BIG_AND_UGLY because it's clear and easy for programers to see where CORE starts and your own code begins (if you've chosen your own name space, you should...)

See: <a href="http://downloads.sourceforge.net/project/just-core/DOCS/Diagrams/JCORE_Stack.jpg" target="_new">JCORE Stack</a> to get a sense of how the frame work is structured.


Simple:
	Development:
		- developers can worry about what they need to get done, 
                  not basic functionality/basic due diligence (error checking every query etc.)
		
		- built in, dirt simple templating system. 
                        - can output any text based file  
                        - used previously in production environments for 
                               - HTML, CSS, PHP, XML
		
		- SOA and "plugin" architecture 
			- separate transport layer can handle 
			       - JSONRPC (AJAX), HTTP, SOAP, etc...
				- all processing should be separate from the transport layer, 
				- reuse the same business logic for different API's
			
		- built in logging 
                        - for performance, BI and log to multiple facilities: 
                          SYSLOG (PHP default), UDP, DB etc.

		- DB access through "DSN" [Data Source Name] and central interface
			- integrated connection manager
			      - add as many DB's as you need 
			      - connections are instantiated only on request to the DB
			- literally CRUD API methods
                              - Create, Retrieve, Update, Delete, Raw 
			- access your database(s) in POS [Plain Old SQL for MySQL/Postgres]
                              - NOSQL & memory stores to come
			- get an array, object or raw PHP resource as the result 

		- DAO (Data Access Objects)
                        - simplified persistence for most common entity structures
                               - no ORM bloat
                        - easy to prototype, easy to extend and optimize
	         		- new object creation from table introspection
		        	- internal changes tracked and stored on decomposition 
			- built in master/slave relationship in DSN name space 
				- instantiate from the slave (or master)
				- save to the master on decomposition or request (MySQL && Postgres)
		
		- built in cache management
			- leverage common opcode caches
			     - APC, xcache, Eaccelerator
			- use opcode, memory or nosql for data caching 
			- common api format with built in hooks at the data layer
			     - write through, write back, write your own
		
	Deployment:
		- just-core and "stub projects" use composer to manage auto include 
		    paths for package management including this core package
		   [Composer: Getting Started](https://getcomposer.org/doc/00-intro.md)

		- *.php based configurations 
			- easy syntax for programmers to understand, manage and maintain
			- can be changed @ runtime 
			- should be OPCODE cached for higher performance

	Resource Management:
		- deploy a common code base over multiple applications
			- shorten the learning curve when reassigning your resources 
		

* see www.opengroup.org/togaf/ for more information on The Open Group Architectural Framework and the TOGAF Architectural Development Model

licensed under: http://www.opensource.org/licenses/osl-3.0
plugins and packages may use what ever license they wish. JCORE [just-core] is designed to separate the foundation layer from the business logic and implementation