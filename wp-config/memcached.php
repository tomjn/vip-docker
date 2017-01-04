<?php

define('WP_CACHE', true);

global $memcached_servers;
$memcached_servers = array(
	array(
		'memcached',
		11211,
	)
);
