<?php get_header(); ?>

<!-- メンテナンスページ -->
<section class="maintenance-hero-section relative h-96 w-full flex items-center justify-center overflow-hidden">
    <!-- 背景画像 -->
    <div class="absolute inset-0 bg-center bg-no-repeat" 
         style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2025/08/kurouchi-2.png'); background-size: cover; background-position: center; background-color: #1a1a1a;">
        <!-- オーバーレイ -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>
    
    <!-- ヒーローコンテンツ -->
    <div class="relative z-10 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-lg">包丁メンテナンス</h1>
        <p class="text-lg md:text-xl text-shadow">大切な包丁を新品同様の切れ味に蘇らせます</p>
    </div>
</section>

<!-- メインコンテンツ -->
<section class="maintenance-content py-16 md:py-24 bg-ink">
    <div class="container-custom">
        
        <!-- メンテナンスの流れ -->
        <div class="maintenance-flow mb-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">包丁メンテナンスの流れ</h2>
                <p class="text-lg text-white">簡単4ステップで、大切な包丁を蘇らせます</p>
            </div>
            
            <div class="flow-steps grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- ステップ1: お問い合わせ -->
                <div class="flow-step">
                    <div class="card rounded-lg p-6 md:p-8 h-full text-center" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="step-number mb-6">
                            <div class="w-16 h-16 bg-wood text-ink font-bold text-2xl rounded-full flex items-center justify-center mx-auto">
                                1
                            </div>
                        </div>
                        
                        <div class="step-icon mb-4">
                            <svg class="w-12 h-12 text-wood mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-3">お問い合わせ</h3>
                        <p class="text-white text-sm leading-relaxed">
                            お問い合わせフォームから、メンテナンスを依頼したい包丁の写真をアップロードしてください。詳細を確認し、包丁の状態を把握します。
                        </p>
                    </div>
                </div>

                <!-- ステップ2: お見積もり -->
                <div class="flow-step">
                    <div class="card rounded-lg p-6 md:p-8 h-full text-center" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="step-number mb-6">
                            <div class="w-16 h-16 bg-wood text-ink font-bold text-2xl rounded-full flex items-center justify-center mx-auto">
                                2
                            </div>
                        </div>
                        
                        <div class="step-icon mb-4">
                            <svg class="w-12 h-12 text-wood mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-3">お見積もり</h3>
                        <p class="text-white text-sm leading-relaxed">
                            専門スタッフが写真から包丁の状態を確認し、お見積もり金額をメールでお知らせします。確認後、ご承諾いただいた場合に次のステップに進みます。
                        </p>
                    </div>
                </div>

                <!-- ステップ3: 包丁の発送 -->
                <div class="flow-step">
                    <div class="card rounded-lg p-6 md:p-8 h-full text-center" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="step-number mb-6">
                            <div class="w-16 h-16 bg-wood text-ink font-bold text-2xl rounded-full flex items-center justify-center mx-auto">
                                3
                            </div>
                        </div>
                        
                        <div class="step-icon mb-4">
                            <svg class="w-12 h-12 text-wood mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-3">包丁の発送</h3>
                        <p class="text-white text-sm leading-relaxed">
                            お見積もりにご同意いただけたら、ご自宅から市蔵刃物本舗にレターパックで包丁を発送してください。発送先情報はメールにてお送りします。
                        </p>
                    </div>
                </div>

                <!-- ステップ4: メンテナンス完了 & お届け -->
                <div class="flow-step">
                    <div class="card rounded-lg p-6 md:p-8 h-full text-center" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="step-number mb-6">
                            <div class="w-16 h-16 bg-wood text-ink font-bold text-2xl rounded-full flex items-center justify-center mx-auto">
                                4
                            </div>
                        </div>
                        
                        <div class="step-icon mb-4">
                            <svg class="w-12 h-12 text-wood mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-3">メンテナンス完了<br>& お届け</h3>
                        <p class="text-white text-sm leading-relaxed">
                            2～4週間のメンテナンス期間を経て、仕上がった包丁をご自宅までお届けします。新品同様の切れ味をお楽しみください。
                        </p>
                    </div>
                </div>

            </div>
            
            <!-- お問い合わせボタン -->
            <div class="text-center mt-12">
                <div class="mb-6">
                    <p class="text-lg text-white mb-4">お問い合わせはこちらから</p>
                </div>
                <a href="<?php echo home_url('/contact/'); ?>" 
                   class="inline-block px-8 py-4 bg-wood text-ink font-bold text-lg rounded-lg transition-all duration-200 hover:bg-wood/90 transform hover:scale-105">
                    お問い合わせフォーム
                </a>
            </div>
        </div>

        <!-- メンテナンス事例 -->
        <div class="maintenance-cases">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">包丁メンテナンス事例</h2>
                <p class="text-lg text-white">実際のメンテナンス事例をご紹介します</p>
            </div>
            
            <div class="cases-grid space-y-12">
                
                <!-- 事例1 -->
                <div class="case-item max-w-4xl mx-auto">
                    <div class="card rounded-lg p-6 md:p-8" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-8 text-center">事例 1</h3>
                        
                        <!-- ビフォー・アフター -->
                        <div class="before-after grid md:grid-cols-2 gap-6 md:gap-8 mb-8">
                            <div class="before">
                                <h4 class="text-xl font-bold text-wood mb-4 text-center">ビフォー</h4>
                                <div class="image-placeholder bg-iron/20 rounded-lg h-64 md:h-80 flex items-center justify-center">
                                    <p class="text-white text-lg">ビフォー画像</p>
                                </div>
                            </div>
                            
                            <div class="after">
                                <h4 class="text-xl font-bold text-wood mb-4 text-center">アフター</h4>
                                <div class="image-placeholder bg-iron/20 rounded-lg h-64 md:h-80 flex items-center justify-center">
                                    <p class="text-white text-lg">アフター画像</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="case-description text-center">
                            <p class="text-white text-lg leading-relaxed mb-6 max-w-2xl mx-auto">
                                研ぎに失敗し、切れ味が悪く、腹の部分もムラになった包丁が見事によみがえりました。
                            </p>
                            
                            <div class="price-info">
                                <p class="text-wood font-bold text-xl">
                                    参考価格：3,500円(税込) + 送料
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 事例2 -->
                <div class="case-item max-w-4xl mx-auto">
                    <div class="card rounded-lg p-6 md:p-8" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-8 text-center">事例 2</h3>
                        
                        <!-- ビフォー・アフター -->
                        <div class="before-after grid md:grid-cols-2 gap-6 md:gap-8 mb-8">
                            <div class="before">
                                <h4 class="text-xl font-bold text-wood mb-4 text-center">ビフォー</h4>
                                <div class="image-placeholder bg-iron/20 rounded-lg h-64 md:h-80 flex items-center justify-center">
                                    <p class="text-white text-lg">ビフォー画像</p>
                                </div>
                            </div>
                            
                            <div class="after">
                                <h4 class="text-xl font-bold text-wood mb-4 text-center">アフター</h4>
                                <div class="image-placeholder bg-iron/20 rounded-lg h-64 md:h-80 flex items-center justify-center">
                                    <p class="text-white text-lg">アフター画像</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="case-description text-center">
                            <p class="text-white text-lg leading-relaxed mb-6 max-w-2xl mx-auto">
                                持ち手が外れて長年使えなくなっていたお客様の大切な包丁を修理いたしました。
                            </p>
                            
                            <div class="price-info">
                                <p class="text-wood font-bold text-xl">
                                    参考価格：7,000円(税込) + 送料
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CTA セクション -->
        <div class="maintenance-cta mt-20">
            <div class="card rounded-lg p-8 md:p-12 text-center" style="background-color: #2A2A2A66; border: 1px solid rgba(255, 255, 255, 0.1);">
                <h3 class="text-3xl md:text-4xl font-bold text-white mb-6">大切な包丁を蘇らせませんか？</h3>
                
                <p class="text-lg text-white mb-8 leading-relaxed max-w-3xl mx-auto">
                    切れ味が悪くなった包丁、研ぎに失敗してしまった包丁、持ち手が壊れた包丁など、<br class="hidden md:block">
                    どのような状態でもお気軽にご相談ください。<br class="hidden md:block">
                    熟練の職人が、一本一本丁寧にメンテナンスいたします。
                </p>
                
                <div class="cta-buttons flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo home_url('/contact/'); ?>" 
                       class="inline-block px-8 py-4 bg-wood text-ink font-bold text-lg rounded-lg transition-all duration-200 hover:bg-wood/90 transform hover:scale-105">
                        メンテナンス依頼をする
                    </a>
                    
                    <a href="https://ichizoknives.base.shop/items/all" target="_blank" rel="noopener noreferrer"
                       class="inline-block px-8 py-4 bg-transparent border-2 border-wood text-wood font-bold text-lg rounded-lg transition-all duration-200 hover:bg-wood hover:text-ink">
                        新しい包丁を見る
                    </a>
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

.maintenance-content .card {
    transition: transform 0.2s ease;
}

.maintenance-content .card:hover {
    transform: translateY(-2px);
}

.flow-steps {
    position: relative;
}

.flow-step {
    position: relative;
}

/* ステップ間の矢印（デスクトップのみ） */
@media (min-width: 1024px) {
    .flow-step:not(:last-child)::after {
        content: '→';
        position: absolute;
        top: 50%;
        right: -1rem;
        transform: translateY(-50%);
        color: #D4A574;
        font-size: 1.5rem;
        font-weight: bold;
        z-index: 10;
    }
}

.image-placeholder {
    border: 2px dashed rgba(212, 165, 116, 0.3);
}

@media (max-width: 768px) {
    .maintenance-hero-section {
        height: 300px;
    }
    
    .maintenance-content {
        padding: 2rem 0;
    }
    
    .flow-steps {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .before-after {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .image-placeholder {
        height: 200px !important;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
}
</style>

<?php get_footer(); ?>
