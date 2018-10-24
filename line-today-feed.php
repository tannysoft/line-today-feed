<?php
/**
* Plugin Name: Line Today Feed by Tannysoft
* Plugin URI: https://www.tannysoft.com/
* Description: Auto send feed for line today application.
* Version: 1.0
* Author: Tannysoft
* Author URI: https://www.tannysoft.com
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain: line-today-feed
*/

// Register a URL that will set this variable to true
add_action( 'init', 'ltd_feed_init' );
function ltd_feed_init() {
	add_rewrite_rule( '^line-today-feed$', 'index.php?ltd_feed_stats=true', 'top' );
	flush_rewrite_rules();
}

// But WordPress has a whitelist of variables it allows, so we must put it on that list
add_action( 'query_vars', 'ltd_feed_query_vars' );
function ltd_feed_query_vars( $query_vars )
{
    $query_vars[] = 'ltd_feed_stats';
    return $query_vars;
}

// If this is done, we can access it later
// This example checks very early in the process:
// if the variable is set, we include our page and stop execution after it
add_action( 'parse_request', 'ltd_feed_parse_request' );
function ltd_feed_parse_request( &$wp )
{
    if ( array_key_exists( 'ltd_feed_stats', $wp->query_vars ) ) {
        include( dirname( __FILE__ ) . '/data/list.php' );
        exit();
    }
}
