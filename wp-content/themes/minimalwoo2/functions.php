<?php
/**
 * Theme Functions - Vetements Theme
 * Author: Alicia
 */

// Setup du thème
function simpliste_vetements_setup() {
    // Support des images mises en avant
    add_theme_support('post-thumbnails');
    
    // Support du titre automatique
    add_theme_support('title-tag');
    
    // Support WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Support HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Support logo personnalisé
    add_theme_support('custom-logo');
    
    // Menu principal
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'simpliste-vetements'),
    ));
}
add_action('after_setup_theme', 'simpliste_vetements_setup');

// Enqueue des styles et scripts
function simpliste_vetements_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0');
    wp_enqueue_script('theme-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'simpliste_vetements_scripts');

// Widget sidebar
function simpliste_vetements_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'simpliste-vetements'),
        'id'            => 'sidebar-1',
        'description'   => __('Sidebar principale', 'simpliste-vetements'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'simpliste_vetements_widgets_init');

/* ========================================
   HOOKS PERSONNALISÉS (2 requis pour le devoir)
   ======================================== */

// HOOK 1 : Ajouter du contenu après le titre du produit
function simpliste_custom_after_product_title() {
    global $product;
    if ($product && $product->is_on_sale()) {
        echo '<span class="custom-badge sale-badge">🔥 En Promotion</span>';
    }
    if ($product && !$product->is_in_stock()) {
        echo '<span class="custom-badge stock-badge">⏳ Rupture de stock</span>';
    }
}
add_action('woocommerce_single_product_summary', 'simpliste_custom_after_product_title', 6);

// HOOK 2 : Modifier le texte du bouton "Ajouter au panier"
function simpliste_custom_add_to_cart_text($text) {
    global $product;
    
    if ($product) {
        switch ($product->get_type()) {
            case 'simple':
                return 'Ajouter à mon panier';
            case 'variable':
                return 'Choisir les options';
            case 'grouped':
                return 'Voir les produits';
            default:
                return $text;
        }
    }
    
    return $text;
}
add_filter('woocommerce_product_single_add_to_cart_text', 'simpliste_custom_add_to_cart_text');
add_filter('woocommerce_product_add_to_cart_text', 'simpliste_custom_add_to_cart_text');

// Personnaliser le nombre de produits par page
function simpliste_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'simpliste_products_per_page', 20);

// Personnaliser les colonnes de produits
function simpliste_loop_columns() {
    return 3; // 3 colonnes sur desktop
}
add_filter('loop_shop_columns', 'simpliste_loop_columns');
?>
