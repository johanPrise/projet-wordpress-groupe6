<?php
/**
 * Plugin Name: Comment Shop - Témoignages Clients
 * Plugin URI: https://exemple.com
 * Description: Système de témoignages clients affichables sur homepage/sidebar avec notes étoiles
 * Version: 1.0.0
 * Author: Johan PRISO
 * Author URI: https://exemple.com
 * Text Domain: comment-shop
 * Domain Path: /languages
 */

if( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define('CS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CS_VERSION', '1.0.0');


/**
 * Classe principale du plugin
 */
class Comment_Shop{
    private static $instance = null;

    public static function get_instance(){
            if (null === self::$instance){
                self::$instance = new self();
            }
            return self::$instance;
    }

    private function __construct(){
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        // Charger les classes avec vérification
        $files = array(
            'includes/class-cpt-avis.php',
            'includes/class-metaboxes.php',
            'includes/class-formulaire.php',
            'includes/class-shortcodes.php',
            'includes/class-widget.php'
        );
        
        foreach ($files as $file) {
            $filepath = CS_PLUGIN_PATH . $file;
            if (file_exists($filepath)) {
                require_once $filepath;
            } else {
                error_log('Comment Shop: Fichier manquant - ' . $filepath);
            }
        }
    }


    private function init_hooks() {
        // Activer les classes au chargement de WordPress
        add_action('plugins_loaded', array($this, 'init_classes'));
        
        // Charger CSS et JS
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Hook d'activation du plugin
        register_activation_hook(__FILE__, array($this, 'activate'));
    }


    public function init_classes() {
        // Créer les instances des classes si elles existent
        if (class_exists('CS_CPT_Avis')) {
            CS_CPT_Avis::get_instance();
        }
        
        if (class_exists('CS_Metaboxes')) {
            CS_Metaboxes::get_instance();
        }
        
        if (class_exists('CS_Formulaire')) {
            CS_Formulaire::get_instance();
        }
        
        if (class_exists('CS_Shortcodes')) {
            CS_Shortcodes::get_instance();
        }
        
        if (class_exists('CS_Widget')) {
            CS_Widget::get_instance();
        }
    }


     public function enqueue_scripts() {
        // CSS
        wp_enqueue_style(
            'comment-shop-style',
            CS_PLUGIN_URL . 'assets/css/avis-styles.css',
            array(),
            CS_VERSION
        );
        
        // JavaScript
        wp_enqueue_script(
            'comment-shop-ajax',
            CS_PLUGIN_URL . 'assets/js/avis-ajax.js',
            array('jquery'),
            CS_VERSION,
            true
        );
        
        // Passer variables PHP à JavaScript
        wp_localize_script('comment-shop-ajax', 'commentShopAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cs_ajax_nonce')
        ));
    }

     public function enqueue_admin_scripts() {
        wp_enqueue_style(
            'comment-shop-admin',
            CS_PLUGIN_URL . 'assets/css/admin-avis.css',
            array(),
            CS_VERSION
        );
    }

    public function activate() {
        // Créer le CPT
        CS_CPT_Avis::get_instance();
        
        // Flush rewrite rules (pour les URLs)
        flush_rewrite_rules();
    }
}

/**
 * Démarrer le plugin
 */
function comment_shop_init() {
    return Comment_Shop::get_instance();
}

// Lancer le plugin
comment_shop_init();

