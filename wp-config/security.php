<?php

define( 'DISALLOW_FILE_EDIT', true );

if ( $_ENV['WP_PRODUCTION'] ) {
	define( 'FORCE_SSL_ADMIN', true );
}
