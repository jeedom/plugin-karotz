#!/bin/bash
#$1 = [host]
#$2 = directory of files
cd $2
set -x
which lftp
if [ $? == 1 ]; then
	sudo apt-get install lftp
fi
lftp <<EOF
set ftp:ssl-allow no
set ftp:passive-mode true
set ftp:list-options -a
open -u karotz, $1
mkdir -f /usr/www/cgi-bin/install
put -O /usr/www/cgi-bin/install/ install.sh -o install
put -O /usr/www/cgi-bin/install/ setvolume -o setvolume.krz
put -O /usr/www/cgi-bin/install/ status -o status.krz
put -O /usr/www/cgi-bin/install/ utils.inc -o utils.inc.krz
EOF
exit $?