<?php
/**
 * Plugin Name: BASE Debug Settings
 * Description: Debug BASE API settings
 */

if (!defined('ABSPATH')) { exit; }

// Debug page for admin only
add_action('admin_menu', function () {
    add_submenu_page(
        'options-general.php',
        'BASE Debug',
        'BASE Debug',
        'manage_options',
        'ichizo-base-debug',
        function () {
            if (!current_user_can('manage_options')) return;
            
            echo '<div class="wrap">';
            echo '<h1>BASE設定デバッグ</h1>';
            
            echo '<h2>定数 (Constants)</h2>';
            echo '<table class="widefat">';
            echo '<tr><td>BASE_SHOP_ID</td><td>' . (defined('BASE_SHOP_ID') ? BASE_SHOP_ID : '未定義') . '</td></tr>';
            echo '<tr><td>BASE_CLIENT_ID</td><td>' . (defined('BASE_CLIENT_ID') ? BASE_CLIENT_ID : '未定義') . '</td></tr>';
            echo '<tr><td>BASE_CLIENT_SECRET</td><td>' . (defined('BASE_CLIENT_SECRET') ? '***設定済み***' : '未定義') . '</td></tr>';
            echo '<tr><td>BASE_REDIRECT_URI</td><td>' . (defined('BASE_REDIRECT_URI') ? BASE_REDIRECT_URI : '未定義') . '</td></tr>';
            echo '</table>';
            
            echo '<h2>環境変数 (Environment)</h2>';
            echo '<table class="widefat">';
            echo '<tr><td>BASE_SHOP_ID</td><td>' . (getenv('BASE_SHOP_ID') ?: '未設定') . '</td></tr>';
            echo '<tr><td>BASE_CLIENT_ID</td><td>' . (getenv('BASE_CLIENT_ID') ?: '未設定') . '</td></tr>';
            echo '<tr><td>BASE_CLIENT_SECRET</td><td>' . (getenv('BASE_CLIENT_SECRET') ? '***設定済み***' : '未設定') . '</td></tr>';
            echo '<tr><td>BASE_REDIRECT_URI</td><td>' . (getenv('BASE_REDIRECT_URI') ?: '未設定') . '</td></tr>';
            echo '</table>';
            
            echo '</div>';
        }
    );
});
