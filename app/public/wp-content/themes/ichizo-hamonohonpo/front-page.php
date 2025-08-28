<?php get_header(); ?>

<!-- ヒーロー画像セクション -->
<section class="hero-section relative h-screen w-full flex items-center justify-center overflow-hidden">
    <!-- 背景画像 -->
    <div class="absolute inset-0 bg-center bg-no-repeat hero-background" 
         style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2025/07/sashimi-2.png'); background-size: cover; background-position: center top; background-color: #1a1a1a;">
        <!-- オーバーレイ -->
        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
    </div>
    
</section>

<!-- 縦書きテキストセクション -->
<section class="vertical-text-section py-12 md:py-20 bg-ink bg-texture mobile-section-py">
    <div class="container-custom">
        <div class="flex justify-center">
            <!-- 全サイズ共通の縦書き -->
            <div class="text-white vertical-responsive">
                <!-- デスクトップ用縦書き -->
                <div class="hidden md:block vertical-text-unified" style="height: 600px; font-size: 1.6875rem;">
                    <p style="writing-mode: vertical-rl; text-orientation: mixed; font-family: 'Shippori Antique', serif; letter-spacing: 0.1em; line-height: 2.2; font-size: 1.6875rem;">
                        刃物のセレクトショップ<br>
                        市蔵刃物本舗<br><br>
                        熟練の目利きによって<br>
                        厳選された刃物を取り揃え、<br><br>
                        プロから家庭の料理愛好家まで<br>
                        幅広いお客様に愛用されています。<br><br>
                        選び抜かれた品質が日々の作業や料理を、<br>
                        より快適で楽しいものにします。
                    </p>
                </div>
                <!-- モバイル用縦書き -->
                <div class="md:hidden vertical-text-mobile" style="font-family: 'Shippori Antique', serif;">
                    <p style="writing-mode: vertical-rl; text-orientation: mixed; font-family: 'Shippori Antique', serif; letter-spacing: 0.05em; line-height: 1.5;">
                        刃物のセレクトショップ<br>
                        市蔵刃物本舗<br><br>
                        熟練の目利きによって<br>
                        厳選された刃物を取り揃え、<br><br>
                        プロから家庭の料理愛好家まで<br>
                        幅広いお客様に愛用されています。<br><br>
                        選び抜かれた品質が日々の作業や料理を、<br>
                        より快適で楽しいものにします。
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container-custom"><div class="divider"></div></div>

<!-- 製品グリッド（API未接続の仮レイアウト） -->
<section class="products-section py-12 md:py-20 bg-ink bg-texture mobile-section-py">
	<div class="container-custom">
		<div class="section-header mb-8 md:mb-10">
			<h2 class="section-title text-2xl md:text-3xl font-bold text-white text-center mb-2 section-title-mobile">製品紹介</h2>
			<p class="section-subtitle text-white text-center text-sm md:text-base section-subtitle-mobile">選りすぐりの一品</p>
		</div>
		<?php 
			// 取得数を多めにしてから前段でフィルタ → 最終的に6件へ
			$requested = 12; // フィルタで減る想定に備えて多めに取得
			$raw_items = function_exists('ichizo_get_base_items') ? ichizo_get_base_items($requested) : array();
			$base_items = array();
			if (!empty($raw_items)) {
				foreach ($raw_items as $it) {
					$url   = isset($it['url']) ? (string) $it['url'] : '';
					$image = isset($it['image']) ? (string) $it['image'] : '';
					if (empty($image) || empty($url)) { continue; }
					if (strpos($url, '/items/all') !== false || preg_match('#/all$#', $url)) { continue; }
					$base_items[] = $it;
					if (count($base_items) >= 6) { break; }
				}
			}
		?>
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mobile-grid md:max-w-none md:mx-0">
			<?php if (!empty($base_items)) : ?>
				<?php foreach ($base_items as $item) : ?>
					<?php
						$url   = isset($item['url']) ? (string) $item['url'] : '';
						$image = isset($item['image']) ? (string) $item['image'] : '';
						// 画像が無い、または /items/all（/all を含む）へのリンクは表示しない
						if (empty($image) || empty($url) || strpos($url, '/items/all') !== false || preg_match('#/all$#', $url)) {
							continue;
						}
					?>
					<article class="card product-card border border-iron/20 rounded-md overflow-hidden bg-ink/40 group">
						<a href="<?php echo esc_url($url); ?>" class="block" target="_blank" rel="noopener noreferrer">
							<div class="product-image-wrapper aspect-square overflow-hidden">
								<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw" />
							</div>
							<div class="p-4">
								<h3 class="card-title product-title text-base font-medium text-white"><?php echo esc_html($item['title']); ?></h3>
								<?php if (!empty($item['price'])) : ?>
									<p class="product-price mt-2 text-white">¥<?php echo number_format(intval($item['price'])); ?></p>
								<?php endif; ?>
							</div>
						</a>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<!-- フォールバック（設定前など） -->
				<?php for ($i=0; $i<6; $i++) : ?>
				<article class="card product-card border border-iron/20 rounded-md overflow-hidden bg-ink/40 group">
					<div class="product-image-wrapper aspect-square overflow-hidden bg-iron/20"></div>
					<div class="p-4">
						<h3 class="card-title product-title text-base font-medium text-white">商品名（BASE設定待ち）</h3>
						<p class="product-price mt-2 text-white">—</p>
					</div>
				</article>
				<?php endfor; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<div class="container-custom"><div class="divider"></div></div>

<main id="main" class="site-main">
    <?php
    // 追加のコンテンツがあればここに
    ?>

    <!-- 最新のnote（RSS連携） -->
    <section class="note-section py-12 md:py-20 bg-ink bg-texture mobile-section-py">
        <div class="container-custom">
            <div class="section-header mb-8 md:mb-10">
                <h2 class="section-title text-2xl md:text-3xl font-bold text-white text-center mb-2 section-title-mobile">最新のnote</h2>
                <div class="text-center">
                    <a class="section-subtitle hover:text-wood transition-colors text-sm md:text-base text-white section-subtitle-mobile" href="https://note.com/ichizo_hamonoho" target="_blank" rel="noopener noreferrer">もっと見る →</a>
                </div>
            </div>
            <?php $note_items = function_exists('ichizo_get_note_items') ? ichizo_get_note_items(3) : array(); ?>
            <?php if (!empty($note_items)) : ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 mobile-grid md:max-w-none md:mx-0">
                    <?php foreach ($note_items as $note) : ?>
                        <article class="card note-card border border-iron/20 rounded-md overflow-hidden bg-ink/40 group">
                            <a href="<?php echo esc_url($note['url']); ?>" class="block" target="_blank" rel="noopener noreferrer">
                                <div class="aspect-video overflow-hidden bg-iron/20 flex items-center justify-center">
                                    <?php if (!empty($note['image'])) : ?>
                                        <img src="<?php echo esc_url($note['image']); ?>" alt="<?php echo esc_attr($note['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                    <?php else : ?>
                                        <!-- noteロゴプレースホルダー -->
                                        <div class="text-iron text-sm font-medium">note</div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <time class="text-xs text-iron"><?php echo esc_html($note['date']); ?></time>
                                    <h3 class="card-title mt-2 text-base font-medium text-white leading-snug">
                                        <?php echo esc_html($note['title']); ?>
                                    </h3>
                                    <p class="mt-2 text-sm text-iron leading-relaxed">
                                        <?php echo esc_html($note['summary']); ?>
                                    </p>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-iron">現在、noteの記事を取得できませんでした。</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?> 