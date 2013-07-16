
What is Gyroscope?
==================
- A flexible way to represent and manipulate data, known as the "Gyroscope View"
- An application framework that allows rapid implementation of the Gyroscope View
- A set of standardized business processes that leverage the strength of Gyroscope Applications

Core Components of the Gyroscope Framework
==========================================
- Layout Manager
- Tab System
- Auto Complete
- Form Validation
- Authentication

In this Package
===============
- A barebone Gyroscope template
- A demo project simplified from a real-life application
- Documentation
- Quick tips and indexes to Antradar's web development resource

Installation
============
Gyroscope requires a HTTP server that's capable of running PHP.
In addition, the authentication module requires a relational database to store user credentials.
A common setup is Apache + PHP + MySQL.

In the sample and template folders there is a htdocs folder.
Copy the content of this folder to the web root or its subdirectories.

By default, both the sample and template applications authenticate the users.
You need to import the data from the perspective data folders and configure the connection string in connect.php.

Two users are enabled upon installation:
	admin:demo
and
	guest:demo

Files to modify:
	auth.php (recommanded)
	connect.php (mendatory)

License
=======
Use of this framework must be made known to end users;
Modification allowed;
Closed source or commercial usage allowed;
Must not be used to compete with Antradar products

www.antradar.com/license.php

Documentation
=============
www.antradar.com/docs.php?article=gyroscope-doc

	