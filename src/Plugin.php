<?php

namespace Voce\SiteWidePassword;

require_once dirname( __FILE__ ) . '/NetworkSettings.php';
require_once dirname( __FILE__ ) . '/SiteSettings.php';

class Plugin {

	public function init() {
		add_filter( 'template_include', [ $this, 'filterTemplateInclude' ] );
		add_action( 'do_feed', [ $this, 'disableRss' ], 1);
		add_action( 'do_feed_rdf', [ $this, 'disableRss' ], 1);
		add_action( 'do_feed_rss', [ $this, 'disableRss' ], 1);
		add_action( 'do_feed_rss2', [ $this, 'disableRss' ], 1);
		add_action( 'do_feed_atom', [ $this, 'disableRss' ], 1);
		$network_settings = new NetworkSettings;
		$network_settings->init();

		$site_settings = new SiteSettings;
		$site_settings->init();
	}

	/**
	 * Prompt for site password if user isn't logged or user is logged in and not an admin
	 */
	public function filterTemplateInclude( $template ) {
		$swp_site_settings    = SiteSettings::getSettings();
		$swp_network_settings = NetworkSettings::getSettings();

		// site settings take precedent over network settings
		if ( ! empty( $swp_site_settings['active'] ) && ! empty( $swp_site_settings['password'] ) ) {
			$swp_settings = $swp_site_settings;
		} elseif ( ! empty( $swp_network_settings['active'] ) && ! empty( $swp_network_settings['password'] ) ) {
			$swp_settings = $swp_network_settings;
		} else {
			$swp_settings = false;
		}

		$messages     = [ ];
		if ( ! is_admin() && ! empty( $swp_settings ) && $swp_settings['active'] && ! empty( $swp_settings['password'] ) ) {
			// if user is admin, don't stop them
			if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
				return $template;
			}

			// check if user has cookie with correct password
			$site_wide_password = empty( $_COOKIE['swp'] ) ? false : $_COOKIE['swp'];
			if ( ! empty( $site_wide_password ) && $swp_settings['password'] ) {
				if ( $_COOKIE['swp'] === $this->generate_cookie_hash( $swp_settings['password'], get_current_user_id() ) || $_COOKIE['swp'] === $this->generate_cookie_hash( $swp_settings['password'], 0 ) ) {
					return $template;
				}
			}

			// if this is a submission of the swp password, set the cookie
			if ( ! empty( $_POST['swp_action'] ) && 'submit_password' === $_POST['swp_action'] ) {
				// check if nonce is correct
				$nonce = empty( $_POST['swp_wpnonce'] ) ? false : $_POST['swp_wpnonce'];
				if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'swp_password_submission' ) ) {
					$messages[] = 'Invalid submission, please try again.';
				}
				// check if password if correct
				$password = empty( $_POST['swp_password'] ) ? false : $_POST['swp_password'];
				if ( empty( $password ) ) {
					$messages[] = 'Please enter a password.';
				} elseif ( $swp_settings['password'] !== $password ) {
					$messages[] = 'Incorrect password.';
				} else {
					setcookie( 'swp', $this->generate_cookie_hash( $password, get_current_user_id() ) );
					return $template;
				}
			}

			include_once dirname( __FILE__ ) . '/tmpl/password.php';
			die;

		}
		return $template;
	}

	private function generate_cookie_hash( $password, $user_id ) {
		return wp_hash( sprintf( '%s_%s', $user_id, $password ) );
	}

	public function disableRss() {
		$site_settings       = SiteSettings::getSettings();
		$network_settings    = NetworkSettings::getSettings();
		$site_disable_rss    = ! empty( $site_settings[ 'disable-rss' ] );
		$network_disable_rss = ! empty( $network_settings[ 'disable-rss' ] );
		if ( $site_disable_rss || $network_disable_rss ){
			wp_die( 'No feeds available.' );
		}
	}
}