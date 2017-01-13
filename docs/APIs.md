#APIS

An API in JCORE is an HTTP or CLI exposed directory that will load a specific set of plugins.
Given that JCORE is explicitly SOA service objects/sets are the primary area of focus in application development. 

###Basic API directory Structure

    [API_NAME]/
        index.php
        config.php


####index.php
index.php is the "harness" for the API


####config.php
config.php defines all the settings for the API

###API Configuration settings 
[[include ref=API%20CONFIGURATION]]

###default_admin_http
###default_http