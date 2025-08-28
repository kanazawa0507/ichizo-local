<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- ファビコン -->
    <link rel="icon" type="image/jpeg" href="<?php echo home_url(); ?>/wp-content/uploads/2025/07/logo_ichizo6.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="<?php echo home_url(); ?>/wp-content/uploads/2025/07/logo_ichizo6.jpg">
    <link rel="apple-touch-icon" href="<?php echo home_url(); ?>/wp-content/uploads/2025/07/logo_ichizo6.jpg">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('font-sans antialiased bg-ink text-white'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header border-b border-iron/20">
        <div class="container-custom">
            <div class="flex items-center justify-between py-3 md:py-4">
                <div class="site-branding">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1 class="site-title text-xl md:text-2xl font-bold text-wood">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="hover:text-white transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu toggle -->
                <button id="menu-toggle" class="md:hidden p-2 rounded border border-iron/30 text-white focus:outline-none focus:ring-2 focus:ring-wood/50" aria-controls="primary-menu" aria-expanded="false">
                    <span class="sr-only">メニュー</span>
                    <span aria-hidden="true">☰</span>
                </button>

                <div class="flex items-center space-x-4">
                    <nav id="site-navigation" class="main-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu-list',
                            'menu_class'     => 'flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 lg:space-x-6 text-white text-sm lg:text-base whitespace-nowrap',
                            'container'      => 'div',
                            'container_id'   => 'primary-menu',
                            'container_class'=> 'hidden md:block',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </nav>
                    
                    <!-- SNS Links -->
                    <div class="hidden md:flex items-center space-x-2 ml-3 pl-3 border-l border-iron/30">
                        <a href="https://www.instagram.com/ichizo_knives" target="_blank" rel="noopener noreferrer" 
                           class="p-2 rounded-full bg-iron/10 hover:bg-wood/20 text-white hover:text-wood transition-all duration-300 transform hover:scale-110 group"
                           title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span class="sr-only">Instagram</span>
                        </a>
                        
                        <a href="https://www.tiktok.com/@ichizo_knives" target="_blank" rel="noopener noreferrer" 
                           class="p-2 rounded-full bg-iron/10 hover:bg-wood/20 text-white hover:text-wood transition-all duration-300 transform hover:scale-110 group"
                           title="TikTok">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                            <span class="sr-only">TikTok</span>
                        </a>
                        

                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="content" class="site-content"> 