<?php
/*
Plugin Name: Wishlist 
Author: Alicia
Description: plugin wishlist
Version: 1.0
*/

// Sécurité : empêcher accès direct
if ( !defined('ABSPATH') ) exit;

// Activer la session pour stocker la wishlist
function wishlist_start_session() {
    if(!session_id()) {
        session_start();
    }
}
add_action('init', 'wishlist_start_session');

// Ajouter un produit à la wishlist
function wishlist_add_item() {
    if(isset($_GET['wishlist_add'])) {
        $product_id = intval($_GET['wishlist_add']);
        if(!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array();
        }
        if(!in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $product_id;
        }
        wp_redirect(remove_query_arg('wishlist_add'));
        exit;
    }
}
add_action('template_redirect', 'wishlist_add_item');

// Shortcode pour afficher la wishlist
function wishlist_display() {
    $output = "<h2>Ma Wishlist</h2>";
    if(!empty($_SESSION['wishlist'])) {
        $output .= "<ul>";
        foreach($_SESSION['wishlist'] as $id) {
            $output .= "<li>";
            $output .= get_the_title($id);
            $output .= " - <a href='".get_permalink($id)."'>Voir</a>";
            $output .= " - <a href='".add_query_arg('wishlist_remove', $id)."'>Retirer</a>";
            $output .= "</li>";
        }
        $output .= "</ul>";
    } else {
        $output .= "<p>Votre wishlist est vide.</p>";
    }
    return $output;
}
add_shortcode('wishlist', 'wishlist_display');

// Retirer un produit
function wishlist_remove_item() {
    if(isset($_GET['wishlist_remove'])) {
        $product_id = intval($_GET['wishlist_remove']);
        if(isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], array($product_id));
        }
        wp_redirect(remove_query_arg('wishlist_remove'));
        exit;
    }
}
add_action('template_redirect', 'wishlist_remove_item');

// Ajouter un bouton "Ajouter à la wishlist" sous chaque produit
function wishlist_button($content) {
    if(is_singular('product')) {
        global $post;
        $link = add_query_arg('wishlist_add', $post->ID);
        $content .= "<p><a href='".$link."' class='wishlist-btn'>❤️ Ajouter à ma wishlist</a></p>";
    }
    return $content;
}
add_filter('the_content', 'wishlist_button');
