import sys
import telnetlib

if len(sys.argv) > 1:
    HOST = sys.argv[1]
else:
    print "error : ip parameter missing"
    sys.exit(1)
#HOST = "192.168.0.24"
user = "karotz";

tn = telnetlib.Telnet(HOST)

tn.read_until("login: ")
tn.write(user + "\n")
tn.read_until("4.1# ")
tn.write("cd /usr/www/cgi-bin/install\n")
tn.write("chmod a+x install\n")
tn.write("exit\n")
print tn.read_all()