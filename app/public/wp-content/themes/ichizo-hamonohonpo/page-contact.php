<?php get_header(); ?>

<!-- お問い合わせページ -->
<section class="contact-hero-section relative h-96 w-full flex items-center justify-center overflow-hidden">
    <!-- 背景画像 -->
    <div class="absolute inset-0 bg-center bg-no-repeat" 
         style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2025/08/kurouchi-2.png'); background-size: cover; background-position: center; background-color: #1a1a1a;">
        <!-- オーバーレイ -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>
    
    <!-- ヒーローコンテンツ -->
    <div class="relative z-10 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-lg">お問い合わせ</h1>
        <p class="text-lg md:text-xl text-shadow">ご質問やご相談がございましたら、お気軽にお問い合わせください</p>
    </div>
</section>

<!-- お問い合わせフォームセクション -->
<section class="contact-form-section py-16 md:py-24 bg-ink">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto">
            
            <!-- お問い合わせフォーム - 中央配置 -->
            <div class="flex justify-center">
                <div class="contact-form-wrapper w-full max-w-2xl">
                    <div class="card bg-ink/40 border border-iron/20 rounded-lg p-8 md:p-12 shadow-2xl">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-8 text-center">お問い合わせフォーム</h3>
                        
                        <form id="contact-form" class="space-y-6" method="post" enctype="multipart/form-data">
                            <?php wp_nonce_field('contact_form_submit', 'contact_nonce'); ?>
                            
                            <div class="form-group">
                                <label for="contact-name" class="block text-white font-medium mb-2">お名前 <span class="text-red-400">*</span></label>
                                <input type="text" id="contact-name" name="contact_name" required 
                                       class="w-full px-4 py-3 bg-ink border border-iron/30 rounded-md text-white placeholder-iron/60 focus:outline-none focus:border-wood focus:ring-2 focus:ring-wood/20 transition-all duration-200"
                                       placeholder="市蔵 刃物">
                            </div>

                            <div class="form-group">
                                <label for="contact-email" class="block text-white font-medium mb-2">メールアドレス <span class="text-red-400">*</span></label>
                                <input type="email" id="contact-email" name="contact_email" required 
                                       class="w-full px-4 py-3 bg-ink border border-iron/30 rounded-md text-white placeholder-iron/60 focus:outline-none focus:border-wood focus:ring-2 focus:ring-wood/20 transition-all duration-200"
                                       placeholder="ichizo@example.com">
                            </div>

                            <div class="form-group">
                                <label for="contact-phone" class="block text-white font-medium mb-2">電話番号</label>
                                <input type="tel" id="contact-phone" name="contact_phone" 
                                       class="w-full px-4 py-3 bg-ink border border-iron/30 rounded-md text-white placeholder-iron/60 focus:outline-none focus:border-wood focus:ring-2 focus:ring-wood/20 transition-all duration-200"
                                       placeholder="090-1234-5678">
                            </div>



                            <div class="form-group">
                                <label for="contact-message" class="block text-white font-medium mb-2">お問い合わせ内容 <span class="text-red-400">*</span></label>
                                <textarea id="contact-message" name="contact_message" rows="6" required 
                                          class="w-full px-4 py-3 bg-ink border border-iron/30 rounded-md text-white placeholder-iron/60 focus:outline-none focus:border-wood focus:ring-2 focus:ring-wood/20 resize-vertical transition-all duration-200"
                                          placeholder="お問い合わせ内容をご記載ください。"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="contact-files" class="block text-white font-medium mb-2">
                                    添付ファイル（任意）
                                    <span class="text-iron/60 text-sm font-normal ml-2">※ 画像、PDF、Word文書（最大5MB、複数選択可）</span>
                                </label>
                                <div class="file-upload-wrapper">
                                    <input type="file" 
                                           id="contact-files" 
                                           name="contact_files[]" 
                                           multiple 
                                           accept="image/*,.pdf,.doc,.docx,.txt"
                                           class="hidden"
                                           onchange="updateFileList(this)">
                                    <label for="contact-files" 
                                           class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-iron/30 rounded-lg cursor-pointer bg-iron/5 hover:bg-iron/10 transition-colors duration-200">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-iron/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-iron/60">
                                                <span class="font-semibold">クリックしてファイル選択</span> またはドラッグ&ドロップ
                                            </p>
                                            <p class="text-xs text-iron/50">
                                                JPG, PNG, PDF, Word文書など
                                            </p>
                                        </div>
                                    </label>
                                    <div id="file-list" class="mt-3 space-y-2"></div>
                                </div>
                            </div>

                            <div class="form-group pt-4">
                                <button type="submit" class="btn-primary w-full py-4 text-lg font-medium rounded-md transition-all duration-200 hover:transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-wood/30 shadow-lg">
                                    お問い合わせを送信
                                </button>
                            </div>
                        </form>
                        
                        <div id="form-message" class="mt-4 hidden"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for form handling -->
<script>
// ファイルリスト表示機能
function updateFileList(input) {
    const fileList = document.getElementById('file-list');
    const files = Array.from(input.files);
    
    fileList.innerHTML = '';
    
    if (files.length === 0) {
        return;
    }
    
    files.forEach((file, index) => {
        // ファイルサイズチェック (5MB制限)
        const maxSize = 5 * 1024 * 1024; // 5MB
        const isValidSize = file.size <= maxSize;
        
        const fileItem = document.createElement('div');
        fileItem.className = `flex items-center justify-between p-3 bg-iron/10 rounded-lg border ${isValidSize ? 'border-iron/30' : 'border-red-400'}`;
        
        const fileInfo = document.createElement('div');
        fileInfo.className = 'flex items-center space-x-3';
        
        // ファイルアイコン
        const icon = document.createElement('div');
        icon.innerHTML = getFileIcon(file.type);
        
        // ファイル詳細
        const details = document.createElement('div');
        details.innerHTML = `
            <div class="text-white text-sm font-medium">${file.name}</div>
            <div class="text-iron/60 text-xs">${formatFileSize(file.size)} ${isValidSize ? '' : '- サイズが大きすぎます'}</div>
        `;
        
        fileInfo.appendChild(icon);
        fileInfo.appendChild(details);
        
        // 削除ボタン
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'text-iron/60 hover:text-red-400 transition-colors';
        removeBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        removeBtn.onclick = () => removeFile(index, input);
        
        fileItem.appendChild(fileInfo);
        fileItem.appendChild(removeBtn);
        fileList.appendChild(fileItem);
    });
}

// ファイルアイコン取得
function getFileIcon(fileType) {
    if (fileType.startsWith('image/')) {
        return '<svg class="w-6 h-6 text-wood" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
    } else if (fileType === 'application/pdf') {
        return '<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
    } else {
        return '<svg class="w-6 h-6 text-iron/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
    }
}

// ファイルサイズフォーマット
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// ファイル削除
function removeFile(index, input) {
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    updateFileList(input);
}

// ドラッグ&ドロップ機能
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('contact-files');
    const dropZone = document.querySelector('label[for="contact-files"]');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropZone.classList.add('bg-iron/20', 'border-wood');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('bg-iron/20', 'border-wood');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        fileInput.files = files;
        updateFileList(fileInput);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const messageDiv = document.getElementById('form-message');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // ファイルサイズチェック
        const fileInput = document.getElementById('contact-files');
        const files = Array.from(fileInput.files);
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        for (let file of files) {
            if (file.size > maxSize) {
                showMessage('error', 'ファイルサイズが大きすぎます（最大5MB）: ' + file.name);
                return;
            }
        }
        
        const formData = new FormData(form);
        formData.append('action', 'handle_contact_form');

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = '送信中...';
        submitBtn.disabled = true;

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // デバッグ用
            
            if (data && data.success) {
                messageDiv.className = 'mt-6 p-6 rounded-lg bg-green-900/70 border-2 border-green-400 text-green-100 font-medium text-center shadow-lg';
                const message = data.data || data.message || 'お問い合わせありがとうございます。<br>内容を確認のうえ、ご連絡いたします。';
                messageDiv.innerHTML = '<div class="flex items-center justify-center space-x-2"><svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>' + message + '</span></div>';
                form.reset();
            } else {
                messageDiv.className = 'mt-6 p-6 rounded-lg bg-red-900/70 border-2 border-red-400 text-red-100 font-medium text-center shadow-lg';
                const message = (data && (data.data || data.message)) || 'エラーが発生しました。';
                messageDiv.innerHTML = '<div class="flex items-center justify-center space-x-2"><svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg><span>' + message + '</span></div>';
            }
            messageDiv.classList.remove('hidden');
        })
        .catch(error => {
            messageDiv.className = 'mt-6 p-6 rounded-lg bg-red-900/70 border-2 border-red-400 text-red-100 font-medium text-center shadow-lg';
            messageDiv.innerHTML = '<div class="flex items-center justify-center space-x-2"><svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg><span>エラーが発生しました。しばらく時間をおいて再度お試しください。</span></div>';
            messageDiv.classList.remove('hidden');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>

<?php get_footer(); ?>
