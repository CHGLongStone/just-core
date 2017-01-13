#Framework Structure


####JCORE/[APIS]
contains example API's (SOA, ReST, basic HTTP) as well as the "default_admin_api" 
if you want to expose this you can create a symlink under the http directory
see [just-core apis](https://github.com/CHGLongStone/just-core-stub/wiki#jcoreapis)

####JCORE/[CONFIG]

see: [APPLICATION CONFIGURATION](https://github.com/CHGLongStone/just-core-stub/wiki#application-configuration)

####JCORE/[CORE:foundation]
[vendor/just-core/foundation/CORE the service bus at the foundation](https://github.com/CHGLongStone/just-core/wiki/just-core-service-bus)

 

 * Authentication/Authorization 
 * Cache (opcode and data) 
 * Data layer (connection management) 
 * DAO (basic Data Access Objects) 
 * Exception
 * Localization 
 * Load (bootstrap and autoload )
 * Templater 
 * Transport layer 

Given that JCORE is explicitly SOA (Service Oriented Architecture), plugins are meant to be discrete service objects/sets. 

####JCORE/[TEMPLATES]
example templates for various file types CSS, HTML, Javascript, PHP and XML
