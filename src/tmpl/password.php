<?php get_header(); ?>
<div class="site-main swp-password-template">
    <main class="content-area content-area-full-width" role="main">
        <div class="post-well">
            <h2>Password Protected</h2>
            <form method="post">
                <div class="swp-messages">
                    <?php if ( ! empty( $messages ) && is_array( $messages ) ) : ?>
                        <?php foreach( $messages as $message ) : ?>
                            <p class="alert alert-warning" role="alert"><?php esc_html_e( $message ); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <fieldset>
                    <label for="swp_password">Please enter the site password</label>
                    <input type="password" id="swp_password" name="swp_password" />
                </fieldset>
                <?php wp_nonce_field( 'swp_password_submission', 'swp_wpnonce'); ?>
                <input type="hidden" name="swp_action" value="submit_password" />
                <div class="form-submit">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </main>
</div>
<?php get_footer();