<?php


return array(
	"JCORE" => array(
		"FOUNDATION" => array(
			/**
			* SETTING_2="hi_im_setting_2",
			* SETTING_3="hi_im_setting_3",
			* SETTING_4="hi_im_setting_4",
			* API INFO IS NOT INCLUDED HERE
			*/
			"ENVIRONMENT" 	=>"DEV" ,#DEV/UAT/PROD
			"ENVPATH" 		=> '/var/www/JCORE',
			"CACHEPATH" 	=> '/var/www/JCORE',
			"PLUGINSPATH" 	=> '/var/www/JCORE',
			"PACKAGESPATH" 	=> '/var/www/JCORE',
			"LOGPATH" 		=> '/var/log/httpd/',
		),
		"DATE" => array(
			/**
			* for the date format you can pass constants 
			*	'DATE_ATOM' => 'Y-m-d\\TH:i:sP',
			*	'DATE_COOKIE' => 'l, d-M-y H:i:s T',
			*	'DATE_ISO8601' => 'Y-m-d\\TH:i:sO',
			*	'DATE_RFC822' => 'D, d M y H:i:s O',
			*	'DATE_RFC850' => 'l, d-M-y H:i:s T',
			*	'DATE_RFC1036' => 'D, d M y H:i:s O',
			*	'DATE_RFC1123' => 'D, d M Y H:i:s O',
			*	'DATE_RFC2822' => 'D, d M Y H:i:s O',
			*	'DATE_RFC3339' => 'Y-m-d\\TH:i:sP',
			*	'DATE_RSS' => 'D, d M Y H:i:s O',
			*	'DATE_W3C' => 'Y-m-d\\TH:i:sP',
			*/
			"FORMAT" => "Y-m-d",	#http://www.php.net/manual/en/function.date.php
			"FORMAT_LONG" => "l jS \of F Y h:i:s A"  ,
		),
		"SERVICES" => array(
			"DATA" => "",
			"EXCEPTION" => "",
			"LOG" => "",
			"DAO" => "",
			"CACHE" => "",
			"AUTH" => "",
		),
	)

);

?>