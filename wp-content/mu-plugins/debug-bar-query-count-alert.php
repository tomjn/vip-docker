<?php
/*
Plugin Name: Debug Bar Query Count Alert
Version: 0.1
Description: A simple add-on for the Debug Bar plugin to replace the button text with the database query count and time
Author: Matthew Boynes
Author URI: http://boyn.es
Plugin URI: https://github.com/mboynes/debug-bar-query-count-alert
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


if ( !class_exists( 'Debug_Bar_Query_Count_Button' ) ) :

class Debug_Bar_Query_Count_Button {

	/**
	 * Define the query count limits. The first index [0] triggers a warning, the second [1] an error.
	 *
	 * @var array
	 */
	public $query_count_limits = array( 100, 200 );

	/**
	 * Define the query time limits, in seconds. The first index [0] triggers a warning, the second [1] an error.
	 *
	 * @var array
	 */
	public $query_time_limits = array( 0.5, 1 );


	private static $instance;

	private $query_count;

	private $query_time;

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Debug_Bar_Query_Count_Button;
			self::$instance->setup();
		}
		return self::$instance;
	}


	/**
	 * Add the necessary hooks for the plugin.
	 *
	 * @return void
	 */
	public function setup() {
		# Debug bar gets hooked in at 1000, so we'll sneak in just ahead of it.
		add_action( 'admin_bar_menu',    array( $this, 'get_stats' ), 999 );
		add_filter( 'debug_bar_classes', array( $this, 'classes' ) );
		add_filter( 'debug_bar_title',   array( $this, 'label' ) );
	}


	/**
	 * Get the query count and time, to be used later.
	 *
	 * @return void
	 */
	public function get_stats() {
		global $wpdb;

		$this->query_time = 0;
		foreach ( $wpdb->queries as $query ) {
			$this->query_time += $query[1];
		}

		$this->query_count = get_num_queries();
	}


	/**
	 * Filter the debug bar title, adding in the query count and time.
	 *
	 * @param  string $title The current debug bar title. Defaults to 'Debug Bar'.
	 * @return string        The updated title.
	 */
	public function label( $title ) {
		return sprintf( '%dq/%sms', $this->query_count, number_format( sprintf( '%0.1f', $this->query_time * 1000 ), 1 ) );
	}


	/**
	 * Filter the debug bar button classes, adding in error and warning labels if our
	 * threshold is exceeded.
	 *
	 * @param  array $classes The current array of classes.
	 * @return array          The (potentially) updated set of classes.
	 */
	public function classes( $classes ) {
		if ( $this->query_count > $this->query_count_limits[1] || $this->query_time > $this->query_time_limits[1] ) {
			$classes[] = 'debug-bar-php-warning-summary';
		} elseif ( $this->query_count > $this->query_count_limits[0] || $this->query_time > $this->query_time_limits[0] ) {
			$classes[] = 'debug-bar-php-notice-summary';
		}

		return $classes;
	}
}

if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
	function Debug_Bar_Query_Count_Button() {
		return Debug_Bar_Query_Count_Button::instance();
	}

	Debug_Bar_Query_Count_Button();
}

endif;