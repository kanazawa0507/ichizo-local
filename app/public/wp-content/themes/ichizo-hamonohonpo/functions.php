<?php
// テーマのセットアップ
function ichizo_hamonohonpo_setup() {
    // テーマのサポート機能を追加
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    
    // ナビゲーションメニューの登録
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ichizo-hamonohonpo'),
        'footer' => __('Footer Menu', 'ichizo-hamonohonpo'),
    ));

    // サイトエディタ（ブロックテーマ機能）を無効化し、従来のメニュー画面を復元
    remove_theme_support( 'block-templates' );
}
add_action('after_setup_theme', 'ichizo_hamonohonpo_setup');

// スタイルシートとスクリプトの読み込み
function ichizo_hamonohonpo_enqueue_scripts() {
    // CDNのURLを変数にまとめる
    $tailwind_cdn = 'https://cdn.tailwindcss.com';
    $google_fonts_url = 'https://fonts.googleapis.com/css2?family=Shippori+Antique&display=swap';

    // Tailwind CSS CDN Script
    wp_enqueue_script('tailwindcss-cdn', $tailwind_cdn, array(), null, false);

    // Tailwind CSS設定用のJavaScript
    wp_enqueue_script('tailwind-config', get_template_directory_uri() . '/assets/js/tailwind-config.js', array('tailwindcss-cdn'), wp_get_theme()->get('Version'), false);
    
    // テーマのメインスタイルシート
    // キャッシュ対策: style.css の更新を即時反映（ファイル更新時刻をバージョンに）
    $style_path = get_stylesheet_directory() . '/style.css';
    $style_ver  = file_exists($style_path) ? filemtime($style_path) : wp_get_theme()->get('Version');
    wp_enqueue_style('ichizo-hamonohonpo-style', get_stylesheet_uri(), array(), $style_ver);
    
    // Google Fonts (Shippori Antique)
    wp_enqueue_style('google-fonts-shippori', $google_fonts_url, array(), null);
}
add_action('wp_enqueue_scripts', 'ichizo_hamonohonpo_enqueue_scripts');

// 注: CDN版Tailwindで 'type="text/tailwindcss"' は <script> のみで使用。
// <link rel="stylesheet"> に付けるとブラウザがCSSとして解釈しないため、
// ここでは一切変更しない（text/css のまま）。

// note.com 連携: RSSから最新記事を取得（簡易キャッシュ付き）
function ichizo_get_note_items($max_items = 3) {
    $max_items = max(1, intval($max_items));
    $transient_key = 'ichizo_note_feed_' . $max_items;

    $cached = get_transient($transient_key);
    if ($cached !== false) {
        return $cached;
    }

    include_once ABSPATH . WPINC . '/feed.php';
    $feed_url = 'https://note.com/ichizo_hamonoho/rss';
    $feed = fetch_feed($feed_url);

    if (is_wp_error($feed)) {
        set_transient($transient_key, array(), 10 * MINUTE_IN_SECONDS);
        return array();
    }

    $items = $feed->get_items(0, $max_items);
    $results = array();

    foreach ((array) $items as $item) {
        $title = $item->get_title();
        $permalink = $item->get_permalink();
        $date = $item->get_date('Y-m-d');
        $content = $item->get_content();
        $description = $item->get_description();

        $summary_source = $description ? $description : $content;
        $summary = wp_strip_all_tags($summary_source);
        $summary = wp_trim_words($summary, 30, '…');

        $image = '';
        $enclosure = $item->get_enclosure();
        if ($enclosure && $enclosure->get_link()) {
            $image = esc_url_raw($enclosure->get_link());
        }
        if (!$image && preg_match('/<img[^>]+src="([^"]+)"/i', $content, $m)) {
            $image = esc_url_raw($m[1]);
        }
        
        // noteの場合、OGP画像を取得する
        if (!$image && $permalink) {
            $image = ichizo_get_ogp_image($permalink);
        }
        
        // 画像が取得できない場合は空文字のまま（テンプレート側でプレースホルダー表示）

        $results[] = array(
            'title' => $title,
            'url' => $permalink,
            'date' => $date,
            'summary' => $summary,
            'image' => $image,
        );
    }

    set_transient($transient_key, $results, HOUR_IN_SECONDS);
    return $results;
}

// OGP画像を取得する関数（note対応改善版）
function ichizo_get_ogp_image($url) {
    if (!$url) {
        return '';
    }
    
    $transient_key = 'ichizo_ogp_' . md5($url);
    $cached = get_transient($transient_key);
    if ($cached !== false) {
        return $cached;
    }
    
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'headers' => array(
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'ja,en-US;q=0.7,en;q=0.3',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Upgrade-Insecure-Requests' => '1',
        ),
    ));
    
    if (is_wp_error($response)) {
        set_transient($transient_key, '', 30 * MINUTE_IN_SECONDS);
        return '';
    }
    
    $body = wp_remote_retrieve_body($response);
    if (!$body) {
        set_transient($transient_key, '', 30 * MINUTE_IN_SECONDS);
        return '';
    }
    
    $image = '';
    
    // 1. OGP画像を抽出
    if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $body, $matches)) {
        $image = esc_url_raw($matches[1]);
    } elseif (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\'][^>]*>/i', $body, $matches)) {
        $image = esc_url_raw($matches[1]);
    }
    
    // 2. noteの画像パターンを直接検索
    if (!$image) {
        // note特有のassets.st-note.comの画像を検索
        if (preg_match('/https:\/\/assets\.st-note\.com\/production\/uploads\/images\/[^"\'>\s]+\.(jpg|jpeg|png|gif|webp)/i', $body, $matches)) {
            $image = esc_url_raw($matches[0]);
        }
        // または、noteの記事画像の一般パターン
        elseif (preg_match('/<img[^>]+src=["\']([^"\']*assets\.st-note\.com[^"\']*)["\'][^>]*>/i', $body, $matches)) {
            $image = esc_url_raw($matches[1]);
        }
        // 最後の手段として、最初のimg要素から画像を取得
        elseif (preg_match('/<img[^>]+src=["\']([^"\']*\.(jpg|jpeg|png|gif|webp)(?:\?[^"\']*)?)["\'][^>]*>/i', $body, $matches)) {
            $candidate = esc_url_raw($matches[1]);
            // アイコンやロゴではない、サイズが大きそうな画像のみ採用
            if (!preg_match('/(?:icon|logo|avatar|profile)/i', $candidate)) {
                $image = $candidate;
            }
        }
    }
    
    // キャッシュして返す
    set_transient($transient_key, $image, 12 * HOUR_IN_SECONDS);
    return $image;
}

// ============================
// BASE API 連携（商品一覧）
// ============================

// 管理画面に設定ページを追加
function ichizo_base_settings_menu() {
    add_options_page(
        'BASE API 設定',
        'BASE API',
        'manage_options',
        'ichizo-base-settings',
        'ichizo_base_settings_page'
    );
}
add_action('admin_menu', 'ichizo_base_settings_menu');

// 設定の登録
function ichizo_base_register_settings() {
    register_setting('ichizo_base_settings_group', 'ichizo_base_shop_id');
    register_setting('ichizo_base_settings_group', 'ichizo_base_limit');
}
add_action('admin_init', 'ichizo_base_register_settings');

// 設定ページの描画
function ichizo_base_settings_page() {
    if (!current_user_can('manage_options')) { return; }
    ?>
    <div class="wrap">
        <h1>BASE 連携設定</h1>
        <p>BASEのRSS機能を使って商品情報を表示します。</p>
        <form method="post" action="options.php">
            <?php settings_fields('ichizo_base_settings_group'); ?>
            <?php do_settings_sections('ichizo_base_settings_group'); ?>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="ichizo_base_shop_id">ショップID</label></th>
                    <td>
                        <input name="ichizo_base_shop_id" id="ichizo_base_shop_id" type="text" value="<?php echo esc_attr(get_option('ichizo_base_shop_id', '')); ?>" class="regular-text" placeholder="例: ichizo-hamono" />
                        <p class="description">BASEショップのURL「https://●●●.base.shop/」の●●●部分を入力してください。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ichizo_base_limit">取得件数</label></th>
                    <td><input name="ichizo_base_limit" id="ichizo_base_limit" type="number" value="<?php echo esc_attr(get_option('ichizo_base_limit', 6)); ?>" class="small-text" min="1" max="24" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// BASE から商品を取得（実データ優先 + デバッグ + ローカルフォールバック）
function ichizo_get_base_items($limit = 6) {
    $limit = max(1, intval($limit));
    $shop_id = trim((string) get_option('ichizo_base_shop_id', ''));

    // 環境変数/定数からも取得
    $env_shop  = defined('BASE_SHOP_ID') ? BASE_SHOP_ID : getenv('BASE_SHOP_ID');
    $env_token = defined('BASE_ACCESS_TOKEN') ? BASE_ACCESS_TOKEN : getenv('BASE_ACCESS_TOKEN');
    if (!empty($env_shop)) { $shop_id = $env_shop; }

    // デバッグ/強制ライブ（管理者のみ）
    $debug = current_user_can('manage_options') && isset($_GET['debug_base']);
    $force_live = current_user_can('manage_options') && isset($_GET['force_live']);

    // ローカル環境判定（フォールバック用途）
    $is_local = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', 'ichizo-local.local'], true) ||
                (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], '.local') !== false);

    $transient_key = 'ichizo_base_items_' . $limit;
    $cached = get_transient($transient_key);
    if ($cached !== false && !$debug && !$force_live) {
        return $cached;
    }

    $items = array();

    if ($debug) {
        echo '<h3>BASE API Debug Information</h3>';
        echo '<p>Shop ID from options: ' . (get_option('ichizo_base_shop_id', '') ?: 'NOT SET') . '</p>';
        echo '<p>Shop ID from env/constant: ' . ($env_shop ?: 'NOT SET') . '</p>';
        echo '<p>Final Shop ID: ' . ($shop_id ?: 'NOT SET') . '</p>';
        echo '<p>API Token available: ' . ($api_token ? 'YES (' . substr($api_token, 0, 10) . '...)' : 'NO') . '</p>';
        echo '<p>Local environment: ' . ($is_local ? 'YES' : 'NO') . '</p>';
        echo '<p>Force live: ' . ($force_live ? 'YES' : 'NO') . '</p>';
        echo '<p>Host: ' . $_SERVER['HTTP_HOST'] . '</p>';
    }

    // 方法0: 公式 BASE API（環境変数/定数またはWP設定のトークンがある場合）
    $option_token = get_option('ichizo_base_access_token', '');
    $api_token = $env_token ?: $option_token;
    if (!empty($shop_id) && !empty($api_token)) {
        if ($debug) echo '<p>Trying method 0: Official BASE API...</p>';
        $items = ichizo_fetch_from_base_api($shop_id, $api_token, $limit, $debug);
        if ($debug) echo '<p>Method 0 result: ' . count($items) . ' items</p>';
    } else {
        if ($debug) echo '<p>Method 0 skipped: Missing shop_id or api_token</p>';
    }
    
    // 方法1: RSS (ブログ)
    if (empty($items) && !empty($shop_id)) {
        if ($debug) echo '<p>Trying method 1: RSS...</p>';
        $rss_url = sprintf('https://%s.base.shop/blog.rss', $shop_id);
        $items = ichizo_fetch_from_rss($rss_url, $limit, $debug);
        if ($debug) echo '<p>Method 1 result: ' . count($items) . ' items</p>';
    }
    
    // 方法2: サイトマップから商品URLを収集し、各商品ページのOGPから取得
    if (empty($items) && !empty($shop_id)) {
        if ($debug) echo '<p>Trying method 2: Sitemap...</p>';
        $items = ichizo_fetch_from_sitemap($shop_id, $limit, $debug);
        if ($debug) echo '<p>Method 2 result: ' . count($items) . ' items</p>';
    }

    // 方法3: ALL ITEM ページを直接スクレイピング（無効化：allページ除外のため）
    // if (empty($items) && !empty($shop_id)) {
    //     $items = ichizo_fetch_from_all_items($shop_id, $limit, $debug);
    // }
    if ($debug) echo '<p>Method 3 (ALL ITEM scraping): DISABLED to exclude /items/all</p>';

    // 方法4: トップページをスクレイピング（おすすめ商品等）
    if (empty($items) && !empty($shop_id)) {
        if ($debug) echo '<p>Trying method 4: Homepage scraping...</p>';
        $items = ichizo_fetch_from_html($shop_id, $limit, $debug);
        if ($debug) echo '<p>Method 4 result: ' . count($items) . ' items</p>';
    }

    if ($debug) {
        echo '<pre>Debug BASE Items: ' . print_r($items, true) . '</pre>';
    }

    // ライブ取得が空で、ローカル環境ならプレースホルダーでフォールバック
    if (empty($items) && $is_local && !$force_live) {
        $items = ichizo_get_dummy_base_items($shop_id, $limit);
        if ($debug) echo '<p>Using dummy data for local environment</p>';
    }

    if (!$force_live) {
        set_transient($transient_key, $items, 30 * MINUTE_IN_SECONDS);
    }
    return $items;
}

// ALL ITEMページかどうかを判定する共通関数
function ichizo_is_all_page_url($url) {
    return $url && (strpos($url, '/items/all') !== false || strpos($url, '/all') !== false);
}

// BASE APIキャッシュをクリアする関数
function ichizo_clear_base_cache() {
    global $wpdb;
    // キャッシュをクリア
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_ichizo_base_items_%' OR option_name LIKE '_transient_timeout_ichizo_base_items_%'");
    return 'BASE API cache cleared';
}

// 管理画面にキャッシュクリアボタンを追加
add_action('wp_ajax_clear_base_cache', function() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    echo ichizo_clear_base_cache();
    wp_die();
});

// 公式 BASE API から取得
function ichizo_fetch_from_base_api($shop_id, $access_token, $limit, $debug = false) {
    $endpoint = add_query_arg(
        array(
            'limit' => $limit,
            'order' => 'desc',
        ),
        'https://api.thebase.in/1/items'
    );

    $response = wp_remote_get($endpoint, array(
        'timeout' => 20,
        'headers' => array(
            'X-Base-Access-Token' => $access_token,
            'X-Base-Uid' => $shop_id,
        ),
    ));

    if (is_wp_error($response)) {
        if ($debug) echo '<p>BASE API Error: ' . $response->get_error_message() . '</p>';
        return array();
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    if ($code !== 200 || empty($body)) {
        if ($debug) echo '<p>BASE API HTTP ' . $code . '</p>';
        return array();
    }

    $json = json_decode($body, true);
    if (!is_array($json)) return array();

    $raw_items = array();
    if (isset($json['items'])) {
        $raw_items = $json['items'];
    } elseif (isset($json[0])) {
        $raw_items = $json; // 直接配列
    }

    $items = array();
    foreach ($raw_items as $r) {
        $title = isset($r['title']) ? (string) $r['title'] : (isset($r['name']) ? (string) $r['name'] : '');
        $price = isset($r['price']) ? intval($r['price']) : (isset($r['price_taxin']) ? intval($r['price_taxin']) : 0);
        $id    = isset($r['item_id']) ? $r['item_id'] : (isset($r['id']) ? $r['id'] : null);

        // 画像
        $image = '';
        if (!empty($r['images']) && is_array($r['images'])) {
            $first = $r['images'][0];
            if (is_array($first)) {
                $image = $first['image'] ?? ($first['url'] ?? '');
            } else {
                $image = (string) $first;
            }
            if ($debug) {
                echo '<p>Images array found for item: ' . $title . ' - Image: ' . $image . '</p>';
            }
        }
        if (!$image && !empty($r['img1'])) { 
            $image = $r['img1'];
            if ($debug) echo '<p>Using img1 for: ' . $title . ' - ' . $image . '</p>';
        }
        
        // 追加の画像フィールドをチェック
        if (!$image && !empty($r['image'])) { 
            $image = $r['image'];
            if ($debug) echo '<p>Using image field for: ' . $title . ' - ' . $image . '</p>';
        }
        if (!$image && !empty($r['image_url'])) { 
            $image = $r['image_url'];
            if ($debug) echo '<p>Using image_url for: ' . $title . ' - ' . $image . '</p>';
        }
        
        if ($debug && !$image) {
            echo '<p>No image found for: ' . $title . ' - Raw data: ' . print_r($r, true) . '</p>';
        }

        // URL
        $url = '';
        if (!empty($r['url'])) $url = $r['url'];
        if (!$url && !empty($r['item_url'])) $url = $r['item_url'];
        if (!$url && $id) $url = sprintf('https://%s.base.shop/items/%s', $shop_id, $id);

        // /items/all ページの商品を除外
        $is_all_page = false;
        if ($url && (strpos($url, '/items/all') !== false || strpos($url, '/all') !== false)) {
            $is_all_page = true;
            if ($debug) echo '<p>Skipping ALL page item: ' . $title . ' - URL: ' . $url . '</p>';
        }

        if ($title && !$is_all_page) {
            $items[] = array(
                'title' => $title,
                'price' => $price,
                'image' => $image,
                'url'   => $url,
            );
        }
    }

    return $items;
}
// ローカル環境用ダミーデータ（実際のBASE商品）
function ichizo_get_dummy_base_items($shop_id, $limit) {
    // 実際の画像URLを使用（既存のサイト画像）
    $image1 = home_url() . '/wp-content/uploads/2025/07/sashimi-2.png';
    $image2 = home_url() . '/wp-content/uploads/2025/07/kurouchi.png';
    
    $all_items = array(
        array(
            'title' => '土佐黒打包丁 市蔵 日本製 刃渡り165mm【市蔵刃物本舗】',
            'price' => '12000',
            'image' => $image2,
            'url' => 'https://ichizoknives.base.shop/items/10258635',
        ),
        array(
            'title' => 'ペティナイフ 包丁 市蔵 日本製 刃渡り150mm【市蔵刃物本舗】',
            'price' => '11000',
            'image' => $image1,
            'url' => 'https://ichizoknives.base.shop/items/10258636',
        ),
        array(
            'title' => '三徳包丁 市蔵 日本製 刃渡り165mm【市蔵刃物本舗】',
            'price' => '12000',
            'image' => $image2,
            'url' => 'https://ichizoknives.base.shop/items/10258637',
        ),
        array(
            'title' => '出刃包丁 包丁 市蔵 日本製 刃渡り165mm【市蔵刃物本舗】',
            'price' => '12000',
            'image' => $image1,
            'url' => 'https://ichizoknives.base.shop/items/10258638',
        ),
        array(
            'title' => '薄刃包丁 居近 日本製 刃渡り155mm【市蔵刃物本舗】',
            'price' => '10000',
            'image' => $image2,
            'url' => 'https://ichizoknives.base.shop/items/10258639',
        ),
        array(
            'title' => '刺身包丁 市蔵 日本製 刃渡り240mm【市蔵刃物本舗】',
            'price' => '14000',
            'image' => $image1,
            'url' => 'https://ichizoknives.base.shop/items/10258640',
        ),
    );

    return array_slice($all_items, 0, $limit);
}

// RSSから取得
function ichizo_fetch_from_rss($rss_url, $limit, $debug = false) {
    include_once ABSPATH . WPINC . '/feed.php';
    $feed = fetch_feed($rss_url);

    if (is_wp_error($feed)) {
        if ($debug) echo '<p>RSS Error: ' . $feed->get_error_message() . '</p>';
        return array();
    }

    $feed_items = $feed->get_items(0, $limit);
    $items = array();

    if ($debug) echo '<p>RSS URL: ' . $rss_url . '</p>';
    if ($debug) echo '<p>RSS Items found: ' . count($feed_items) . '</p>';

    foreach ((array) $feed_items as $item) {
        $title = $item->get_title();
        $url = $item->get_permalink();
        $description = $item->get_description();
        
        // 価格を抽出
        $price = '';
        if (preg_match('/¥([0-9,]+)/', $description, $matches)) {
            $price = str_replace(',', '', $matches[1]);
        }
        
        // 画像を抽出
        $image = '';
        if (preg_match('/<img[^>]+src="([^"]+)"/i', $description, $matches)) {
            $image = $matches[1];
        }

        // /items/all ページの商品を除外
        if ($url && (strpos($url, '/items/all') !== false || strpos($url, '/all') !== false)) {
            if ($debug) echo '<p>Skipping ALL page item from RSS: ' . $title . ' - URL: ' . $url . '</p>';
            continue;
        }

        $items[] = array(
            'title' => $title,
            'price' => $price,
            'image' => $image,
            'url' => $url,
        );
    }

    return $items;
}

// サイトマップから商品URLを取得し、各商品ページのOGPで抽出
function ichizo_fetch_from_sitemap($shop_id, $limit, $debug = false) {
    $sitemap_index = sprintf('https://%s.base.shop/sitemaps.xml', $shop_id);
    $response = wp_remote_get($sitemap_index, array('timeout' => 15, 'user-agent' => 'Mozilla/5.0 (compatible; WordPress)'));
    if (is_wp_error($response)) { if ($debug) echo '<p>Sitemap Error: ' . $response->get_error_message() . '</p>'; return array(); }
    $xml = wp_remote_retrieve_body($response);
    if (empty($xml)) return array();

    // itemsサイトマップURLを抽出
    $item_sitemaps = array();
    if (preg_match_all('#<loc>\s*(https?://[^<]*/sitemaps/[^<]*items[^<]*)\s*</loc>#i', $xml, $m)) {
        $item_sitemaps = $m[1];
    }
    // 直接item URLが列挙されている場合にも対応
    $item_urls = array();
    if (empty($item_sitemaps)) {
        if (preg_match_all('#<loc>\s*(https?://[^<]*/items/[^<]*)\s*</loc>#i', $xml, $m2)) {
            // /items/all を除外
            $filtered_urls = array();
            foreach ($m2[1] as $url) {
                if (strpos($url, '/items/all') === false && strpos($url, '/all') === false) {
                    $filtered_urls[] = $url;
                } elseif ($debug) {
                    echo '<p>Skipping ALL page URL from sitemap: ' . $url . '</p>';
                }
            }
            $item_urls = array_slice($filtered_urls, 0, $limit);
        }
    } else {
        foreach ($item_sitemaps as $sm_url) {
            $res = wp_remote_get($sm_url, array('timeout' => 15, 'user-agent' => 'Mozilla/5.0 (compatible; WordPress)'));
            if (is_wp_error($res)) continue;
            $body = wp_remote_retrieve_body($res);
            if (preg_match_all('#<loc>\s*(https?://[^<]*/items/[^<]*)\s*</loc>#i', $body, $mm)) {
                foreach ($mm[1] as $u) {
                    // /items/all を除外
                    if (strpos($u, '/items/all') === false && strpos($u, '/all') === false) {
                        $item_urls[] = $u;
                        if (count($item_urls) >= $limit) break 2;
                    } elseif ($debug) {
                        echo '<p>Skipping ALL page URL from item sitemap: ' . $u . '</p>';
                    }
                }
            }
        }
    }

    if ($debug) echo '<p>Items from sitemap: ' . count($item_urls) . '</p>';
    if (empty($item_urls)) return array();

    $items = array();
    foreach ($item_urls as $u) {
        $og = ichizo_parse_item_og($u, $debug);
        if ($og) {
            $items[] = $og;
            if (count($items) >= $limit) break;
        }
    }
    return $items;
}

// 個別商品ページから OGP を抽出
function ichizo_parse_item_og($url, $debug = false) {
    $res = wp_remote_get($url, array('timeout' => 15, 'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121 Safari/537.36'));
    if (is_wp_error($res)) { if ($debug) echo '<p>Item OGP Error: ' . $res->get_error_message() . '</p>'; return null; }
    $html = wp_remote_retrieve_body($res);
    if (!$html) return null;

    $title = '';
    if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) { $title = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8'); }
    $image = '';
    if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) { $image = $m[1]; }
    $price = '';
    if (preg_match('/<meta[^>]+property=["\']product:price:amount["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) { $price = $m[1]; }
    if (!$price && preg_match('/¥\s*([0-9,]+)/u', $html, $m)) { $price = str_replace(',', '', $m[1]); }

    if (!$title && !$image) return null;
    return array(
        'title' => $title ?: '',
        'price' => $price ?: '',
        'image' => $image ?: '',
        'url'   => $url,
    );
}

// 一時的なキャッシュクリア（デバッグ用）
if (isset($_GET['clear_cache']) && current_user_can('manage_options')) {
    echo '<div style="background: #28a745; color: white; padding: 10px; margin: 20px; border-radius: 5px;">';
    echo ichizo_clear_base_cache();
    echo '</div>';
}
// HTMLスクレイピングで取得
function ichizo_fetch_from_html($shop_id, $limit, $debug = false) {
    $shop_url = sprintf('https://%s.base.shop/', $shop_id);
    
    $response = wp_remote_get($shop_url, array(
        'timeout' => 15,
        'user-agent' => 'Mozilla/5.0 (compatible; WordPress)',
    ));

    if (is_wp_error($response)) {
        if ($debug) echo '<p>HTML Error: ' . $response->get_error_message() . '</p>';
        return array();
    }

    $html = wp_remote_retrieve_body($response);
    if (empty($html)) {
        return array();
    }

    if ($debug) echo '<p>HTML fetched from: ' . $shop_url . '</p>';

    $items = array();
    
    // BASEの商品カードを検索（パターン1: 新しいレイアウト）
    if (preg_match_all('/<a[^>]+href="([^"]*\/items\/[^"]*)"[^>]*>.*?<img[^>]+(?:src|data-src)="([^"]*)"[^>]*>.*?<[^>]*>([^<]+)<.*?¥([0-9,]+)/is', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            if (count($items) >= $limit) break;
            
            $url = ichizo_absolute_url($shop_id, $match[1]);
            // /items/all ページの商品を除外
            if (strpos($url, '/items/all') !== false || strpos($url, '/all') !== false) {
                if ($debug) echo '<p>Skipping ALL page item from HTML: ' . trim(strip_tags($match[3])) . ' - URL: ' . $url . '</p>';
                continue;
            }
            
            $items[] = array(
                'title' => trim(strip_tags($match[3])),
                'price' => str_replace(',', '', $match[4]),
                'image' => ichizo_absolute_url($shop_id, $match[2]),
                'url' => $url,
            );
        }
    }
    
    // パターン2: シンプルな商品リスト
    if (empty($items)) {
        if (preg_match_all('/¥([0-9,]+).*?<.*?>([^<]+包丁[^<]*)<.*?src="([^"]*)".*?href="([^"]*items[^"]*)"/', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (count($items) >= $limit) break;
                
                $url = ichizo_absolute_url($shop_id, $match[4]);
                // /items/all ページの商品を除外
                if (strpos($url, '/items/all') !== false || strpos($url, '/all') !== false) {
                    if ($debug) echo '<p>Skipping ALL page item from HTML pattern 2: ' . trim(strip_tags($match[2])) . ' - URL: ' . $url . '</p>';
                    continue;
                }
                
                $items[] = array(
                    'title' => trim(strip_tags($match[2])),
                    'price' => str_replace(',', '', $match[1]),
                    'image' => ichizo_absolute_url($shop_id, $match[3]),
                    'url' => $url,
                );
            }
        }
    }

    if ($debug) {
        echo '<p>HTML Items found: ' . count($items) . '</p>';
        echo '<pre>Raw HTML (first 1000 chars): ' . substr($html, 0, 1000) . '</pre>';
    }

    return $items;
}

// ALL ITEM ページをスクレイピング
function ichizo_fetch_from_all_items($shop_id, $limit, $debug = false) {
    $url = sprintf('https://%s.base.shop/items', $shop_id);
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'user-agent' => 'Mozilla/5.0 (compatible; WordPress)'
    ));

    if (is_wp_error($response)) {
        if ($debug) echo '<p>ALL ITEM Error: ' . $response->get_error_message() . '</p>';
        return array();
    }

    $html = wp_remote_retrieve_body($response);
    if (empty($html)) { return array(); }

    if ($debug) echo '<p>ALL ITEM fetched: ' . $url . '</p>';

    $items = array();
    // 代表的なカードパターン
    if (preg_match_all('/<a[^>]+href="([^"]*\/items\/[^"]*)"[^>]*>.*?(?:<img[^>]+(?:src|data-src)="([^"]+)"[^>]*>).*?<[^>]*>([^<]+)<.*?¥([0-9,]+)/is', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            if (count($items) >= $limit) break;
            $items[] = array(
                'title' => trim(strip_tags($m[3])),
                'price' => str_replace(',', '', $m[4]),
                'image' => ichizo_absolute_url($shop_id, $m[2]),
                'url' => ichizo_absolute_url($shop_id, $m[1]),
            );
        }
    }

    return $items;
}

// 相対URLを絶対URLへ
function ichizo_absolute_url($shop_id, $url) {
    if (!$url) return '';
    if (preg_match('#^https?://#i', $url)) return $url;
    if (strpos($url, '//') === 0) return 'https:' . $url;
    if ($url[0] === '/') return sprintf('https://%s.base.shop%s', $shop_id, $url);
    return sprintf('https://%s.base.shop/%s', $shop_id, ltrim($url, '/'));
}

// BASEのアイテムをテーマ内共通の形へ正規化
function ichizo_normalize_base_item(array $r, $shop_id) {
    $title = isset($r['title']) ? (string) $r['title'] : (isset($r['name']) ? (string) $r['name'] : '');
    $price = isset($r['price']) ? intval($r['price']) : (isset($r['price_taxin']) ? intval($r['price_taxin']) : 0);
    $id    = isset($r['item_id']) ? $r['item_id'] : (isset($r['id']) ? $r['id'] : null);

    // 画像推定（配列・プロパティ両対応）
    $image = '';
    if (isset($r['images']) && is_array($r['images']) && !empty($r['images'])) {
        $first = $r['images'][0];
        if (is_array($first)) {
            $image = isset($first['image']) ? $first['image'] : (isset($first['url']) ? $first['url'] : '');
        } else {
            $image = (string) $first;
        }
    }
    if (!$image && !empty($r['img1'])) { $image = $r['img1']; }
    if (!$image && !empty($r['image'])) { $image = $r['image']; }
    if (!$image && !empty($r['image_url'])) { $image = $r['image_url']; }

    // URL推定
    $url = '';
    if (!empty($r['url'])) { $url = $r['url']; }
    if (!$url && !empty($r['item_url'])) { $url = $r['item_url']; }
    if (!$url && $shop_id && $id) {
        $url = sprintf('https://%s.base.shop/items/%s', $shop_id, $id);
    }

    return array(
        'title' => $title,
        'price' => $price,
        'image' => $image,
        'url'   => $url,
    );
}

// ========== お問い合わせフォーム機能 ==========

// お問い合わせフォームの処理
function handle_contact_form_submission() {
    // ログインユーザーと非ログインユーザー両方に対応
    $allowed = true;
    
    if (!$allowed) {
        wp_die('Permission denied');
    }

    // Nonceの確認
    if (!wp_verify_nonce($_POST['contact_nonce'], 'contact_form_submit')) {
        wp_send_json_error('セキュリティエラーが発生しました。ページを再読み込みして再度お試しください。');
        return;
    }

    // フォームデータの取得と検証
    $name = sanitize_text_field($_POST['contact_name'] ?? '');
    $email = sanitize_email($_POST['contact_email'] ?? '');
    $phone = sanitize_text_field($_POST['contact_phone'] ?? '');

    $message = sanitize_textarea_field($_POST['contact_message'] ?? '');

    // 必須項目の確認
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('必須項目をすべて入力してください。');
        return;
    }

    // メールアドレスの形式確認
    if (!is_email($email)) {
        wp_send_json_error('正しいメールアドレスを入力してください。');
        return;
    }

    // ファイルアップロード処理
    $attachments = array();
    if (!empty($_FILES['contact_files']['name'][0])) {
        $uploaded_files = $_FILES['contact_files'];
        $file_count = count($uploaded_files['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($uploaded_files['error'][$i] === UPLOAD_ERR_OK) {
                // ファイルサイズチェック (5MB制限)
                $max_size = 5 * 1024 * 1024; // 5MB
                if ($uploaded_files['size'][$i] > $max_size) {
                    wp_send_json_error('ファイルサイズが大きすぎます（最大5MB）: ' . $uploaded_files['name'][$i]);
                    return;
                }
                
                // ファイルタイプチェック
                $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt');
                $file_extension = strtolower(pathinfo($uploaded_files['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($file_extension, $allowed_types)) {
                    wp_send_json_error('許可されていないファイル形式です: ' . $uploaded_files['name'][$i]);
                    return;
                }
                
                // ファイルを一時的にWordPressのアップロードディレクトリに移動
                $upload_dir = wp_upload_dir();
                $temp_file = $upload_dir['path'] . '/' . uniqid() . '_' . sanitize_file_name($uploaded_files['name'][$i]);
                
                if (move_uploaded_file($uploaded_files['tmp_name'][$i], $temp_file)) {
                    $attachments[] = $temp_file;
                }
            }
        }
    }



    // メール送信
    $to = 'info@takaraza.jp';
    $email_subject = '【市蔵刃物本舗】お問い合わせ';
    
    $email_message = "市蔵刃物本舗のWebサイトからお問い合わせがありました。\n\n";
    $email_message .= "【お問い合わせ内容】\n";
    $email_message .= "お名前: " . $name . "\n";
    $email_message .= "メールアドレス: " . $email . "\n";
    $email_message .= "電話番号: " . ($phone ?: '未入力') . "\n";

    $email_message .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n\n";
    $email_message .= "【お問い合わせ内容】\n";
    $email_message .= $message . "\n\n";
    
    // 添付ファイル情報をメール本文に追加
    if (!empty($attachments)) {
        $email_message .= "【添付ファイル】\n";
        foreach ($attachments as $attachment) {
            $filename = basename($attachment);
            $email_message .= "- " . $filename . "\n";
        }
        $email_message .= "\n";
    }
    
    $email_message .= "----\n";
    $email_message .= "送信者IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $email_message .= "ユーザーエージェント: " . $_SERVER['HTTP_USER_AGENT'] . "\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <noreply@' . parse_url(home_url(), PHP_URL_HOST) . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    $mail_sent = wp_mail($to, $email_subject, $email_message, $headers, $attachments);

    if ($mail_sent) {
        // 成功時の自動返信メール（オプション）
        $auto_reply_subject = '【市蔵刃物本舗】お問い合わせを受け付けました';
        $auto_reply_message = $name . " 様\n\n";
        $auto_reply_message .= "この度は市蔵刃物本舗にお問い合わせいただき、誠にありがとうございます。\n";
        $auto_reply_message .= "以下の内容でお問い合わせを受け付けいたしました。\n\n";

        $auto_reply_message .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n\n";
        $auto_reply_message .= "【お問い合わせ内容】\n";
        $auto_reply_message .= $message . "\n\n";
        $auto_reply_message .= "営業時間内（平日9:00-18:00、土曜9:00-17:00）に\n";
        $auto_reply_message .= "担当者よりご連絡させていただきます。\n\n";
        $auto_reply_message .= "----\n";
        $auto_reply_message .= "市蔵刃物本舗\n";
        $auto_reply_message .= "Email: info@takaraza.jp\n";
        $auto_reply_message .= "Website: " . home_url() . "\n";

        $auto_reply_headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <noreply@' . parse_url(home_url(), PHP_URL_HOST) . '>'
        );

        wp_mail($email, $auto_reply_subject, $auto_reply_message, $auto_reply_headers);

        // 一時ファイルを削除
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                unlink($attachment);
            }
        }

        wp_send_json_success('お問い合わせありがとうございます。<br>内容を確認のうえ、ご連絡いたします。');
    } else {
        // メール送信に失敗した場合も一時ファイルを削除
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                unlink($attachment);
            }
        }
        
        wp_send_json_error('メール送信に失敗しました。しばらく時間をおいて再度お試しいただくか、直接お電話にてお問い合わせください。');
    }
}

// AJAX ハンドラーの登録（ログインユーザー・非ログインユーザー両方）
add_action('wp_ajax_handle_contact_form', 'handle_contact_form_submission');
add_action('wp_ajax_nopriv_handle_contact_form', 'handle_contact_form_submission');