<?php get_header(); ?>

<!-- 404エラーページ -->
<section class="error-404-hero-section relative h-96 w-full flex items-center justify-center overflow-hidden">
    <!-- 背景画像 -->
    <div class="absolute inset-0 bg-center bg-no-repeat" 
         style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2025/08/kurouchi-2.png'); background-size: cover; background-position: center; background-color: #1a1a1a;">
        <!-- オーバーレイ -->
        <div class="absolute inset-0 bg-black bg-opacity-70"></div>
    </div>
    
    <!-- ヒーローコンテンツ -->
    <div class="relative z-10 text-center text-white">
        <h1 class="text-6xl md:text-8xl font-bold mb-4 text-shadow-lg">404</h1>
        <p class="text-lg md:text-xl text-shadow">お探しのページが見つかりませんでした</p>
    </div>
</section>

<!-- メインコンテンツ -->
<section class="error-404-content py-16 md:py-24 bg-ink">
    <div class="container-custom">
        <div class="max-w-3xl mx-auto text-center">
            
            <!-- エラーメッセージ -->
            <div class="error-message mb-16">
                <div class="card rounded-lg p-8 md:p-12" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="mb-8">
                        <svg class="w-24 h-24 mx-auto mb-6 text-wood" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.084-2.291M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">ページが見つかりません</h2>
                    
                    <div class="text-white leading-relaxed space-y-4 text-lg mb-8">
                        <p>
                            申し訳ございませんが、お探しのページは存在しないか、移動または削除された可能性があります。
                        </p>
                        
                        <p>
                            以下の方法で目的のページを見つけることができます：
                        </p>
                    </div>
                </div>
            </div>

            <!-- 解決方法 -->
            <div class="solutions-grid grid md:grid-cols-2 gap-8 mb-16">
                
                <!-- ホームページに戻る -->
                <div class="solution-card">
                    <div class="card rounded-lg p-6 md:p-8 h-full" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="mb-4">
                            <svg class="w-12 h-12 text-wood mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">ホームページに戻る</h3>
                        <p class="text-white text-sm mb-4">
                            トップページから目的のコンテンツを探してみてください。
                        </p>
                        <a href="<?php echo home_url('/'); ?>" 
                           class="inline-block px-6 py-3 bg-wood text-ink font-bold rounded-lg transition-all duration-200 hover:bg-wood/90">
                            ホームページへ
                        </a>
                    </div>
                </div>

                <!-- 製品一覧 -->
                <div class="solution-card">
                    <div class="card rounded-lg p-6 md:p-8 h-full" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="mb-4">
                            <svg class="w-12 h-12 text-wood mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">製品一覧を見る</h3>
                        <p class="text-white text-sm mb-4">
                            市蔵刃物本舗の製品をご覧いただけます。
                        </p>
                        <a href="https://ichizoknives.base.shop/items/all" target="_blank" rel="noopener noreferrer"
                           class="inline-block px-6 py-3 bg-wood text-ink font-bold rounded-lg transition-all duration-200 hover:bg-wood/90">
                            製品一覧へ
                        </a>
                    </div>
                </div>

            </div>

            <!-- ナビゲーションリンク -->
            <div class="navigation-links">
                <div class="card rounded-lg p-8" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <h3 class="text-2xl font-bold text-white mb-6">サイト内ナビゲーション</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="<?php echo home_url('/'); ?>" 
                           class="block px-4 py-3 bg-iron/20 text-white rounded-lg hover:bg-wood/20 transition-colors duration-200 text-center">
                            トップページ
                        </a>
                        
                        <a href="<?php echo home_url('/about/'); ?>" 
                           class="block px-4 py-3 bg-iron/20 text-white rounded-lg hover:bg-wood/20 transition-colors duration-200 text-center">
                            市蔵について
                        </a>
                        
                        <a href="<?php echo home_url('/contact/'); ?>" 
                           class="block px-4 py-3 bg-iron/20 text-white rounded-lg hover:bg-wood/20 transition-colors duration-200 text-center">
                            お問い合わせ
                        </a>
                        
                        <a href="https://ichizoknives.base.shop/items/all" target="_blank" rel="noopener noreferrer"
                           class="block px-4 py-3 bg-iron/20 text-white rounded-lg hover:bg-wood/20 transition-colors duration-200 text-center">
                            製品一覧
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- カスタムスタイル -->
<style>
.text-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.text-shadow-lg {
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
}

.error-404-content .card {
    transition: transform 0.2s ease;
}

.error-404-content .card:hover {
    transform: translateY(-2px);
}

.solution-card {
    height: 100%;
}

@media (max-width: 768px) {
    .error-404-hero-section {
        height: 300px;
    }
    
    .error-404-hero-section h1 {
        font-size: 4rem;
    }
    
    .error-404-content {
        padding: 2rem 0;
    }
    
    .error-404-content .card {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .navigation-links .grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}
</style>

<?php get_footer(); ?>
