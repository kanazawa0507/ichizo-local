<?php
/**
 * Plugin Name: BASE Env Loader
 * Description: Load BASE API credentials from environment variables.
 */

// .env 互換（必要ならwp-config.php等でputenv/$_ENV設定）
if (!defined('BASE_SHOP_ID')) {
    $val = getenv('BASE_SHOP_ID');
    if ($val) { define('BASE_SHOP_ID', $val); }
}
if (!defined('BASE_ACCESS_TOKEN')) {
    $val = getenv('BASE_ACCESS_TOKEN');
    if ($val) { define('BASE_ACCESS_TOKEN', $val); }
}


