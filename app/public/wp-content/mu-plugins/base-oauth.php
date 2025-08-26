<?php
/**
 * Plugin Name: BASE OAuth Connector
 * Description: Obtain and store BASE API access tokens via OAuth2 for this WordPress site.
 */

if (!defined('ABSPATH')) { exit; }

// Helper: read env/const
function ichizo_base_env_get($key, $default = '') {
    if (defined($key)) return constant($key);
    $v = getenv($key);
    return $v !== false && $v !== '' ? $v : $default;
}

function ichizo_base_get_redirect_uri() {
    $env = ichizo_base_env_get('BASE_REDIRECT_URI');
    if (!empty($env)) return $env;
    // default: front URL with query flag
    return home_url('/?base_oauth_callback=1');
}

// Handle OAuth callback on front
add_action('init', function () {
    if (!empty($_GET['base_oauth_callback'])) {
        $code = isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '';
        if (!$code) return; // no code

        $client_id = ichizo_base_env_get('BASE_CLIENT_ID', '');
        $client_secret = ichizo_base_env_get('BASE_CLIENT_SECRET', '');
        $redirect_uri = ichizo_base_get_redirect_uri();

        if (empty($client_id) || empty($client_secret)) {
            wp_die('BASE OAuth: client_id/client_secret が未設定です。');
        }

        $token_endpoint = 'https://api.thebase.in/1/oauth/token';
        $response = wp_remote_post($token_endpoint, array(
            'timeout' => 20,
            'body'    => array(
                'grant_type'    => 'authorization_code',
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri'  => $redirect_uri,
                'code'          => $code,
            ),
        ));

        if (is_wp_error($response)) {
            wp_die('BASE OAuth token error: ' . esc_html($response->get_error_message()));
        }

        $code_http = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if ($code_http !== 200 || !is_array($body)) {
            wp_die('BASE OAuth token response error: HTTP ' . intval($code_http));
        }

        $access_token  = isset($body['access_token']) ? $body['access_token'] : '';
        $refresh_token = isset($body['refresh_token']) ? $body['refresh_token'] : '';
        $expires_in    = isset($body['expires_in']) ? intval($body['expires_in']) : 0;

        if ($access_token) {
            update_option('ichizo_base_access_token', $access_token, true);
        }
        if ($refresh_token) {
            update_option('ichizo_base_refresh_token', $refresh_token, true);
        }
        if ($expires_in) {
            update_option('ichizo_base_token_expires_at', time() + $expires_in - 60, true);
        }

        // Redirect to admin settings page after success
        wp_safe_redirect(admin_url('options-general.php?page=ichizo-base-settings&connected=1'));
        exit;
    }
});

// Admin page: Start OAuth
add_action('admin_menu', function () {
    add_submenu_page(
        'options-general.php',
        'BASE OAuth 連携',
        'BASE OAuth 連携',
        'manage_options',
        'ichizo-base-oauth',
        function () {
            if (!current_user_can('manage_options')) return;

            $client_id = ichizo_base_env_get('BASE_CLIENT_ID', '');
            $redirect_uri = ichizo_base_get_redirect_uri();
            $scope = 'read_items';

            if (empty($client_id)) {
                echo '<div class="notice notice-error"><p>BASE_CLIENT_ID と BASE_CLIENT_SECRET を先に設定してください。</p></div>';
            }

            $authorize_url = add_query_arg(array(
                'response_type' => 'code',
                'client_id'     => $client_id,
                'redirect_uri'  => $redirect_uri,
                'scope'         => $scope,
            ), 'https://api.thebase.in/1/oauth/authorize');

            echo '<div class="wrap">';
            echo '<h1>BASE OAuth 連携</h1>';
            echo '<p>以下のボタンからBASEにログイン・許可すると、このサイトにアクセストークンが保存されます。</p>';
            echo '<p><a class="button button-primary button-hero" href="' . esc_url($authorize_url) . '">BASE と連携する</a></p>';

            if (get_option('ichizo_base_access_token')) {
                echo '<p><span style="color: #46b450;">アクセストークンを取得済みです。</span></p>';
            }
            echo '<hr/><p>リダイレクト先: <code>' . esc_html($redirect_uri) . '</code></p>';
            echo '</div>';
        }
    );
});


