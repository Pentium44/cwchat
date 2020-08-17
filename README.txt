CWChat - Chris' Website Chat

Chris's website chat is a simple ajax chat system with a very small footprint 
on web servers. CWChat uses text files for storing chat logs, therefore speeds
the backend of the server. The IRC backend is written in bash and uses telnet to communicate with IRC servers. With that being said, it's not secured by TLS or anything like that. This is in working condition!

Requirements:
	PHP 5.2+
	telnet (IRC backend)
	Read-Write access for .webirc.log and .webirc.input (server side stuffs)
	
Installation:
	~Download CWChat
	~Extract it to desired web server / htdocs directory
	~Chmod .webirc.log and .webirc.input to 777
	~Customize config.php / webirc.conf to your likings
	~Use it!
	
Legal stuff:
	CWChat is based from microchat and has been greatly modified for speed, 
	and agility.
	License: GPLv2
