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