<?php
/**
 * Theme License Protection
 *
 * Keys are tied to the site's domain — a key issued for one domain won't work on another.
 *
 * How to generate a license key for a client:
 *   php -r "echo hash_hmac('sha256', 'client-domain.com', 'YOUR_PRIVATE_SECRET') . PHP_EOL;"
 *
 * Replace 'YOUR_PRIVATE_SECRET' below with your own secret and keep it private.
 */

define( 'NIRVANA_LICENSE_SECRET', 'PCHY_DESIGN_NIRVANA_THERAPY_SECRET_KEY_2026' );
define( 'NIRVANA_LICENSE_OPTION', 'nirvana_theme_license_key' );
define( 'NIRVANA_LICENSE_STATUS_OPTION', 'nirvana_theme_license_status' );

// ---------------------------------------------------------------------------
// Validation
// ---------------------------------------------------------------------------

/**
 * Validate a license key.
 * A valid key is any HMAC-SHA256 hex string produced with NIRVANA_LICENSE_SECRET.
 * To issue a key: hash_hmac( 'sha256', $unique_phrase, NIRVANA_LICENSE_SECRET )
 */
function nirvana_validate_license( string $key ): bool {
    if ( empty( $key ) || strlen( $key ) !== 64 ) {
        return false;
    }
    // Recompute the expected key for this site's domain and compare securely.
    $domain   = wp_parse_url( home_url(), PHP_URL_HOST );
    $expected = hash_hmac( 'sha256', $domain, NIRVANA_LICENSE_SECRET );
    return hash_equals( $expected, strtolower( $key ) );
}

function nirvana_is_licensed(): bool {
    $status = get_option( NIRVANA_LICENSE_STATUS_OPTION, 'inactive' );
    return $status === 'active';
}

// ---------------------------------------------------------------------------
// Admin settings page
// ---------------------------------------------------------------------------

add_action( 'admin_menu', 'nirvana_license_menu' );
function nirvana_license_menu(): void {
    add_theme_page(
        'Theme License',
        'Theme License',
        'manage_options',
        'nirvana-license',
        'nirvana_license_page'
    );
}

add_action( 'admin_init', 'nirvana_license_handle_form' );
function nirvana_license_handle_form(): void {
    if ( ! isset( $_POST['nirvana_license_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nirvana_license_nonce'] ) ), 'nirvana_license_action' ) ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $action = isset( $_POST['nirvana_action'] ) ? sanitize_text_field( $_POST['nirvana_action'] ) : '';

    if ( $action === 'activate' ) {
        $key = isset( $_POST['nirvana_license_key'] ) ? sanitize_text_field( trim( $_POST['nirvana_license_key'] ) ) : '';

        if ( nirvana_validate_license( $key ) ) {
            update_option( NIRVANA_LICENSE_OPTION, $key );
            update_option( NIRVANA_LICENSE_STATUS_OPTION, 'active' );
            add_settings_error( 'nirvana_license', 'activated', 'License activated successfully.', 'updated' );
        } else {
            add_settings_error( 'nirvana_license', 'invalid', 'Invalid license key. Please check the key and try again.' );
        }
    }

    if ( $action === 'deactivate' ) {
        update_option( NIRVANA_LICENSE_STATUS_OPTION, 'inactive' );
        add_settings_error( 'nirvana_license', 'deactivated', 'License deactivated.', 'updated' );
    }
}

function nirvana_license_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $license = get_option( NIRVANA_LICENSE_OPTION, '' );
    $status  = get_option( NIRVANA_LICENSE_STATUS_OPTION, 'inactive' );
    $active  = $status === 'active';
    ?>
    <div class="wrap">
        <h1>Nirvana Theme — License</h1>
        <?php settings_errors( 'nirvana_license' ); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th>Status</th>
                <td>
                    <?php if ( $active ) : ?>
                        <span style="color:#46b450;font-weight:bold;">&#10003; Active</span>
                    <?php else : ?>
                        <span style="color:#dc3232;font-weight:bold;">&#10007; Inactive</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php if ( $active ) : ?>
            <tr>
                <th>License Key</th>
                <td><code><?php echo esc_html( substr( $license, 0, 8 ) . str_repeat( '*', 48 ) . substr( $license, -8 ) ); ?></code></td>
            </tr>
            <?php endif; ?>
        </table>

        <form method="post">
            <?php wp_nonce_field( 'nirvana_license_action', 'nirvana_license_nonce' ); ?>

            <?php if ( ! $active ) : ?>
                <h2>Activate License</h2>
                <p>Enter the license key you received after purchase.</p>
                <table class="form-table" role="presentation">
                    <tr>
                        <th><label for="nirvana_license_key">License Key</label></th>
                        <td>
                            <input
                                type="text"
                                id="nirvana_license_key"
                                name="nirvana_license_key"
                                class="regular-text"
                                placeholder="Paste your license key here"
                                value=""
                                autocomplete="off"
                            />
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="nirvana_action" value="activate" />
                <?php submit_button( 'Activate License' ); ?>
            <?php else : ?>
                <input type="hidden" name="nirvana_action" value="deactivate" />
                <?php submit_button( 'Deactivate License', 'secondary' ); ?>
            <?php endif; ?>
        </form>
    </div>
    <?php
}

// ---------------------------------------------------------------------------
// Admin notice when unlicensed
// ---------------------------------------------------------------------------

add_action( 'admin_notices', 'nirvana_license_admin_notice' );
function nirvana_license_admin_notice(): void {
    if ( nirvana_is_licensed() ) {
        return;
    }
    $page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
    if ( $page === 'nirvana-license' ) {
        return; // Already on the license page — no need to repeat.
    }
    $url = admin_url( 'themes.php?page=nirvana-license' );
    echo '<div class="notice notice-error"><p>';
    echo '<strong>Nirvana Theme:</strong> This theme is not licensed. ';
    echo '<a href="' . esc_url( $url ) . '">Enter your license key</a> to activate.';
    echo '</p></div>';
}

// ---------------------------------------------------------------------------
// Frontend lock — shows a notice overlay until licensed
// ---------------------------------------------------------------------------

add_action( 'wp_head', 'nirvana_license_frontend_lock' );
function nirvana_license_frontend_lock(): void {
    if ( nirvana_is_licensed() ) {
        return;
    }
    // Allow logged-in admins to preview without a license.
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <style>
        #nirvana-license-overlay {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: #CCC8EC;
            color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
            text-align: center;
            padding: 2rem;
        }
        #nirvana-license-overlay h1 { font-size: 1.75rem; margin-bottom: 1rem; color: oklch(28.63% 0.0742 248.4); }
        #nirvana-license-overlay p  { color: oklch(28.63% 0.0742 248.4); max-width: 480px; }
    </style>
    <div id="nirvana-license-overlay">
        <div>
            <h1>Site Unavailable</h1>
            <p>This site is currently unlicensed and will be available once the license has been activated.</p>
        </div>
    </div>
    <?php
}
