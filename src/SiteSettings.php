<?php

namespace Voce\SiteWidePassword;


class SiteSettings {

	protected static function getDefaultSettings() {
		return $defaults = [
				'active'   => false,
				'password' => ''
		];
	}

	public static function getSettings() {
		return wp_parse_args( get_option( 'swp_settings' ), self::getDefaultSettings() );
	}

	public static function setSettings( $newSettings = [ ] ) {
		$settings = self::getSettings();
		$updatedSettings = wp_parse_args( $newSettings, $settings );
		update_option( 'swp_settings', $updatedSettings );
	}

	public function init() {
		add_action( 'admin_init', function() {
			add_settings_section( 'swp_settings', 'Site Wide Password', '', 'general' );

			add_settings_field( 'swp_settings-active', 'Password Active', function() {
				$swp_settings = SiteSettings::getSettings();
				$active       = ! empty( $swp_settings[ 'active' ] );
				?>
				<input type="checkbox" name="swp_settings[active]" id="swp_settings-active" value="1" <?php checked( $active ); ?>/>
				<?php
			}, 'general', 'swp_settings' );

			add_settings_field( 'swp_settings-password', 'Site Password', function() {
				$swp_settings = SiteSettings::getSettings();
				$password     = empty( $swp_settings[ 'password' ] ) ? '' : $swp_settings[ 'password' ];
				?>
				<input type="password" name="swp_settings[password]" id="swp_settings-password" value="<?php echo esc_attr( $password ); ?>"/>
				<?php
			}, 'general', 'swp_settings' );

			register_setting( 'general', 'swp_settings', function() {
				$newSettings = [ ];

				$swp_settings = empty( $_POST[ 'swp_settings' ] ) ? array() : $_POST[ 'swp_settings' ];

				$newSettings[ 'active' ] = ! empty( $swp_settings['active'] );
				$newSettings[ 'password' ] = empty( $swp_settings['password'] ) ? '' : esc_attr( $swp_settings['password'] );

				return $newSettings;
			} );
		} );
	}
	
}