<?php
function simpliste_vetements_setup() {
    // Support des images mises en avant
    add_theme_support('post-thumbnails');
    // Support du titre automatique
    add_theme_support('title-tag');
    // Support WooCommerce
    add_theme_support('woocommerce');
    // Menu principal
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'simpliste-vetements'),
    ));
}
add_action('after_setup_theme', 'simpliste_vetements_setup');

function simpliste_vetements_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'simpliste_vetements_scripts');
?>
