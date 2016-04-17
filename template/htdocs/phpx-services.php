<?php

/*

This script has identical behavior as the script that's defined in $codepage, which is typically "myservices.php".

The web server should listen on both ports, e.g. 8881, 8882, and bind the virtual hosts to the SAME directory.

lb.php should be configured to carry parameters that are forwarded by the load balancer.

The load balancer should send all phpx- scripts to a "priority" virtual host; the rest to the "regular" host.

A sample HAProxy config: (/etc/haproxy/haproxy.cfg)

frontend localnodes
        bind *:80
        mode http
        acl fastlane path_beg /GYROSCOPE_FOLDER/phpx-
        use_backend fastlane if fastlane
        default_backend nodes

backend nodes
		option httpclose
		option forwardfor except 127.0.0.1
        mode http
        balance roundrobin
        server web01 127.0.0.1:8881 check


backend fastlane
		option httpclose
		option forwardfor except 127.0.0.1
        mode http
        balance roundrobin
        server fastlane 127.0.0.1:8882

For HTTPS hosts, the ports and protocols should be adjusted accordingly.

*/

//point to the original fork without (circularly) referencing settings.php

include 'myservices.php';
