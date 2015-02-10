#!/bin/bash
function usage(){

	echo -e "
	"${GREEN}"usage: $0 options"${NC}"

	this runs a \"php -l\" commmand recursively over files 
	in the directories listed in the file in the arg
	usually ./$0 jcore-directory-list
	directories must be listed (full path) one per line. ie.
	output is save to syntax_check.txt
	"
	for i in `cat jcore-directory-list`; do 
		echo $i;
	done	
}

if [ ! $1 ]; then
	usage
	exit 2
fi
echo "************************"
echo "************************"
echo "CHECKING PHP SYNTAX RECURSIVELY UNDER " $PWD;
echo "DIRECTORIES LISTED IN " $1
echo "OUTPUT TO syntax_check.txt ****************"
echo "************************"

for i in `cat $1`; do 
	cd $i;
	echo $PWD;
	find ./ -iname "*.php" -print -exec php -l {} \; > syntax_check.txt
done

echo "*******COMPLETE***********"