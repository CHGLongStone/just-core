# Architectural Principles

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







 