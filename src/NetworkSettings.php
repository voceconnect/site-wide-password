<?php

namespace Voce\SiteWidePassword;


class NetworkSettings {

	protected static function getDefaultSettings() {
		return $defaults = [
				'active'   => false,
				'password' => ''
		];
	}

	public static function getSettings() {
		return wp_parse_args( get_site_option( 'swp_settings' ), self::getDefaultSettings() );
	}

	public static function setSettings( $newSettings = [ ] ) {
		$settings = self::getSettings();
		$updatedSettings = wp_parse_args( $newSettings, $settings );
		update_site_option( 'swp_settings', $updatedSettings );
	}

	public function init() {
		add_action( 'update_wpmu_options', [ $this, 'updateSettings' ] );
		add_action( 'wpmu_options', [ $this, 'displaySettings' ] );
	}

	public function updateSettings() {
		$newSettings = [ ];

		$swp_settings = empty( $_POST[ 'swp_settings' ] ) ? array() : $_POST[ 'swp_settings' ];

		$newSettings[ 'active' ] = ! empty( $swp_settings['active'] );
		$newSettings[ 'password' ] = empty( $swp_settings['password'] ) ? '' : esc_attr( $swp_settings['password'] );

		self::setSettings( $newSettings );
	}

	public function displaySettings() {
		$swp_settings = self::getSettings();

		$active   = ! empty( $swp_settings[ 'active' ] );
		$password = empty( $swp_settings[ 'password' ] ) ? '' : $swp_settings[ 'password' ];
		?>
		<h2><?php _e( 'Site Wide Password' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="swp_settings[active]"><?php _e( 'Password Active' ); ?></label></th>
				<td>
					<input type="checkbox" name="swp_settings[active]" id="swp_settings-active" value="1" <?php checked( $active ); ?>/>
				</td>
			</tr>
			<tr>
				<th><label for="swp_settings[password]"><?php _e( 'Site Password' ); ?></label></th>
				<td>
					<input type="password" name="swp_settings[password]" id="swp_settings-password" value="<?php echo esc_attr( $password ) ?>"/>
				</td>
			</tr>
		</table>
		<?php
	}
}