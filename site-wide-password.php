<?php
/*
 * Plugin Name: Site Wide Password
 * Plugin URI: http://voceconnect.com
 * Description: Blocks access to the front-end of the site unless a user provides a password.
 * Author: prettyboymp, smccafferty
 * Version: 0.1.0
 * License: GPL2+
 */

add_action( 'plugins_loaded', function () {
	spl_autoload_register( function ( $class ) {
		$tln = 'Voce\\SiteWidePassword\\';
		$base_dir = __DIR__ . '/src/';
		$len = strlen( $tln );
		if( strncmp( $tln, $class, $len ) !== 0 ) {
			return;
		}
		$relative_class = substr( $class, $len );
		$file = $base_dir . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';
		if( file_exists( $file ) ) {
			require $file;
		}
	} );

	$plugin = new Voce\SiteWidePassword\Plugin();
	$plugin->init();
} );