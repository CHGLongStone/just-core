#!/bin/bash
function usage(){

	echo -e "
	"${GREEN}"usage: $0 options"${NC}"

	this runs a \"svn diff\" commmand over the directories listed in the file in the arg
	usually jcore-directory-list
	directories must be listed (full path) one per line. ie.
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
echo "CHECKING SVN DIFF"
echo "DIRECTORIES LISTED IN " $1
echo "************************"

for i in `cat $1`; do 
	cd $i;
	echo $PWD;
	svn diff;
done

echo "************************"
echo "************************"
echo "DONE: CHECKING SVN STATUS"
echo "************************"