<?php get_header(); ?>
<div class="site-main swp-password-template">
	<main class="content-area-full-width" role="main">
		<div class="post-well">
			<h2>Password Protected</h2>
			<form method="post">
				<div class="swp-messages">
					<?php if ( ! empty( $messages ) && is_array( $messages ) ) : ?>
						<?php foreach( $messages as $message ) : ?>
							<p><?php esc_html_e( $message ); ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<label for="swp_password">Please enter the site password</label>
				<input type="password" name="swp_password" />
				<?php wp_nonce_field( 'swp_password_submission', 'swp_wpnonce'); ?>
				<input type="hidden" name="swp_action" value="submit_password" />
				<input type="submit" value="Submit"/>
			</form>
		</div>
	</main>
</div>
<?php get_footer();