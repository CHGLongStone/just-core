#!/bin/bash

#######################################
# just-core installer
# Jason Medland jason.medland@gmail.com
#######################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
#######################################
# load the utils script
#######################################
source "$DIR/"utils.sh

function usage(){

	echo -e "
	"${GREEN}"usage: $0 options"${NC}"
	this script will generate and/or copy the default directories 
	required to make a new JCORE application 


	
	\033[1m OPTIONS:\033[0m 
	\033[1m	-h \033[0m     Show this message
	\033[1m	-d \033[0m     directory name
	
	"
}

#echo -e ${BLUE}"$0 $#args[$@] OPTIND[$OPTIND] OPTARG[$OPTARG]"${NC}
while getopts ":v:t:h" name; do
	echo -e "		${purple}FLAG:" $name "VALUE: $OPTARG${NC}"
	#execution_string=$execution_string" -"$name" $OPTARG"
	case $name in
		d)  dflag=1
			dval="$OPTARG";;
		h)   usage 
		exit 2;;
	esac
done

#######################################
#validate the input
#######################################
if [ ! -z "$dflag" ]; then
	echo -e "${green}Creating Directory  ${NC}'"$dval"'"
	mkdir 755 $dval
	cp $DIR../../APIS/stub_AJAX  $dval/API
	cp $DIR../../CACHE  $dval/CACHE
	cp $DIR../../CONFIG  $dval/CONFIG
	cp $DIR../../PACKAGES  $dval/PACKAGES
	cp $DIR../../PLUGINS  $dval/PLUGINS
	cp $DIR../../TEMPLATES  $dval/TEMPLATES
	echo " 
<?php

$ENVPATH = '$DIR';
$CACHEPATH = $dval'/CACHE';

$PLUGINSPATH = $dval'/PLUGINS';
$PACKAGESPATH = 'PACKAGES';
$LOGPATH = '/var/log/apache2/';
?>	
	
	" > $dval/env.php
	
else
	echo -e "${red}NO DIR SET${NC}" 
	echo echo "DIR NAME dval='" $dval "'"
	usage
	exit 2
fi