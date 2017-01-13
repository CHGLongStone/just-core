# Configuration

* [also see: Application Configuration](https://github.com/CHGLongStone/just-core-stub/wiki#application-configuration)

## Configuration Manager

The just-core configuration manager uses a Zend style file pattern matching `*{global,local}.php` to load files from the  `[application_root]/CONFIG/AUTOLOAD` directory in the development environment or the `[application_prod]/cfg` directory 
with `[application_release#]/CONFIG/AUTOLOAD` 

changed to a symbolic link back to 

`[application_prod]/cfg`.

In either case the just-core configuration manager will load all files with the `global` namespace first with any files having a `local` namespace overwriting `global values`. 

#### FILES EXCLUDING "global" or "local" in the namespace WILL NOT be loaded


In the production environment the configuration directory and files will be compiled to a single file to reduce file I/O. The configuration directory in the production environment is also hashed so the contents will be re-compiled when modified. 

Configuration files follow a common format:
```
<?php 
return array(
   'firstIndex' => array(
        /*optional*/
        'secondIndex' => array(
           /*optional*/
           'thirdIndex' => 'array/scalar/...etc.'
        )
    ),
);
?>
```


Configuration options are recalled through the configuration manager which is reference in the $GLOBALS scope `$GLOBALS['CONFIG_MANAGER']->getSetting($firstIndex, $secondIndex, $thirdIndex);`.

* $firstIndex is a required parameter
* $secondIndex, $thirdIndex are optional parameters

`CONFIG_MANAGER` will only look up to the 3rd index for results, if you need data that is nested deeper you will need to parse it within the calling method