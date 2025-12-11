<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container">
        <div class="header-content">
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
            <nav class="main-navigation">
                <?php wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'container' => false,
                    'menu_class' => 'nav-menu'
                )); ?>
            </nav>
            <?php if (function_exists('is_woocommerce') && class_exists('WooCommerce')) : ?>
                <div class="header-cart">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link">
                        <span class="cart-icon">🛒</span>
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>