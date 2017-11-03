#!/bin/bash
set -x
DIR="/usr/www/cgi-bin"
FILES=`ls $DIR/install/*.krz`

for FILE in $FILES
do 
 	filename=$(basename "$FILE")
	extension="${filename##*.}"
	filename="${filename%.*}"
	FILETARGET=$DIR$"/"$filename
	echo "file = $FILETARGET"
	if [ -f $FILETARGET ]; then
		diff $FILE $FILETARGET
		RET=$?
		if [ $RET == 0 ]; then
			echo "already updated; abort"
			chmod a+x $FILETARGET
			rm $FILE
			continue
		fi
		if [ ! $RET == 1 ]; then
			echo "unexpected diff error; abort"
			rm $FILE
			continue
		fi
		for COUNT in `seq 1 9`;
		do
	        if [ ! -f $FILETARGET"."$COUNT ]; then
				cp  $FILETARGET  $FILETARGET"."$COUNT
				cp  -f $FILE  $FILETARGET
				chmod a+x $FILETARGET
				rm $FILE
				echo "file updated; success"
				break
			fi
		done
		if [ -f $FILE ]; then
			echo "backup already exist; abort"
			rm $FILE
		fi
	else
		cp  $FILE  $FILETARGET
		chmod a+x $FILETARGET
		rm $FILE
		echo "file installed; success"
	fi
done