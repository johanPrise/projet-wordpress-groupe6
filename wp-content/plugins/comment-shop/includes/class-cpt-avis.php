<?php
/**
 * Classe pour gérer le Custom Post Type "Avis"
 */

if (!defined('ABSPATH')) {
    exit;
}

class CS_CPT_Avis {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hook pour enregistrer le CPT
        add_action('init', array($this, 'register_post_type'));
        
        // Hook pour ajouter les colonnes dans l'admin
        add_filter('manage_avis_posts_columns', array($this, 'add_admin_columns'));
        add_action('manage_avis_posts_custom_column', array($this, 'fill_admin_columns'), 10, 2);
    }
    
    /**
     * Enregistrer le Custom Post Type
     */
    public function register_post_type() {
        
        $labels = array(
            'name'               => 'Témoignages',
            'singular_name'      => 'Témoignage',
            'menu_name'          => '💬 Témoignages',
            'add_new'            => 'Ajouter un témoignage',
            'add_new_item'       => 'Ajouter un nouveau témoignage',
            'edit_item'          => 'Modifier le témoignage',
            'new_item'           => 'Nouveau témoignage',
            'view_item'          => 'Voir le témoignage',
            'search_items'       => 'Rechercher des témoignages',
            'not_found'          => 'Aucun témoignage trouvé',
            'not_found_in_trash' => 'Aucun témoignage dans la corbeille'
        );
        
        $args = array(
            'labels'              => $labels,
            'description'         => 'Témoignages clients affichables sur homepage/sidebar',
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'temoignages'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 26,
            'menu_icon'           => 'dashicons-star-filled',
            'supports'            => array('title', 'editor', 'author'),
            'show_in_rest'        => true // Support Gutenberg
        );
        
        register_post_type('avis', $args);
    }
    
    /**
     * Ajouter des colonnes dans la liste admin
     */
    public function add_admin_columns($columns) {
        // Réorganiser les colonnes
        $new_columns = array();
        
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = 'Client';
        $new_columns['rating'] = 'Note';
        $new_columns['product'] = 'Produit';
        $new_columns['email'] = 'Email';
        $new_columns['date'] = 'Date';
        
        return $new_columns;
    }
    
    /**
     * Remplir les colonnes personnalisées
     */
    public function fill_admin_columns($column, $post_id) {
        switch ($column) {
            case 'rating':
                $rating = get_post_meta($post_id, '_avis_rating', true);
                echo $this->display_stars($rating);
                break;
                
            case 'product':
                $product_id = get_post_meta($post_id, '_avis_product_id', true);
                if ($product_id) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        echo '<a href="' . get_edit_post_link($product_id) . '">' . $product->get_name() . '</a>';
                    }
                }
                break;
                
            case 'email':
                $email = get_post_meta($post_id, '_avis_email', true);
                echo esc_html($email);
                break;
        }
    }
    
    /**
     * Afficher des étoiles
     */
    private function display_stars($rating) {
        $output = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $output .= '<span style="color: #FFD700;">★</span>';
            } else {
                $output .= '<span style="color: #ccc;">★</span>';
            }
        }
        $output .= ' (' . $rating . '/5)';
        return $output;
    }
}