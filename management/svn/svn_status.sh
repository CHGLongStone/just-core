#!/bin/bash
function usage(){

	echo -e "
	"${GREEN}"usage: $0 options"${NC}"

	this runs a \"svn st\" commmand over the directories listed in the file in the arg
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
echo "CHECKING SVN STATUS"
echo "DIRECTORIES LISTED IN " $1
echo "************************"

for i in `cat $1`; do 
	#echo $i;
	#echo $i;
	cd $i;
	echo $PWD;
	svn st;
done

echo "************************"
echo "************************"
echo "DONE: CHECKING SVN STATUS"
echo "************************"