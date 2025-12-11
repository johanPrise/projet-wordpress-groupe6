<?php
/**
 * Classe pour gérer les metaboxes (champs personnalisés)
 */

if (!defined('ABSPATH')) {
    exit;
}

class CS_Metaboxes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Ajouter les metaboxes
        add_action('add_meta_boxes', array($this, 'add_metaboxes'));
        
        // Sauvegarder les données
        add_action('save_post_avis', array($this, 'save_metabox_data'));
    }
    
    /**
     * Ajouter les metaboxes
     */
    public function add_metaboxes() {
        add_meta_box(
            'avis_details',
            'Détails du témoignage',
            array($this, 'render_metabox'),
            'avis',
            'normal',
            'high'
        );
    }
    
    /**
     * Afficher le contenu de la metabox
     */
    public function render_metabox($post) {
        // Sécurité : nonce
        wp_nonce_field('save_avis_meta', 'avis_meta_nonce');
        
        // Récupérer les valeurs existantes
        $email = get_post_meta($post->ID, '_avis_email', true);
        $rating = get_post_meta($post->ID, '_avis_rating', true);
        $product_id = get_post_meta($post->ID, '_avis_product_id', true);
        $user_id = get_post_meta($post->ID, '_avis_user_id', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="avis_email">Email du client</label></th>
                <td>
                    <input type="email" 
                           id="avis_email" 
                           name="avis_email" 
                           value="<?php echo esc_attr($email); ?>" 
                           class="regular-text" 
                           required>
                </td>
            </tr>
            
            <tr>
                <th><label for="avis_rating">Note (étoiles)</label></th>
                <td>
                    <select id="avis_rating" name="avis_rating" required>
                        <option value="">-- Sélectionner --</option>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                                <?php echo str_repeat('★', $i); ?> (<?php echo $i; ?>/5)
                            </option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <th><label for="avis_product_id">Produit concerné</label></th>
                <td>
                    <select id="avis_product_id" name="avis_product_id" class="regular-text">
                        <option value="">-- Sélectionner un produit --</option>
                        <?php
                        $products = wc_get_products(array('limit' => -1));
                        foreach ($products as $product) :
                        ?>
                            <option value="<?php echo $product->get_id(); ?>" <?php selected($product_id, $product->get_id()); ?>>
                                <?php echo $product->get_name(); ?> (ID: <?php echo $product->get_id(); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <th><label for="avis_user_id">ID Utilisateur</label></th>
                <td>
                    <input type="number" 
                           id="avis_user_id" 
                           name="avis_user_id" 
                           value="<?php echo esc_attr($user_id); ?>" 
                           class="small-text">
                    <p class="description">Laisser vide si client non enregistré</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Sauvegarder les données de la metabox
     */
    public function save_metabox_data($post_id) {
        // Vérifications de sécurité
        if (!isset($_POST['avis_meta_nonce']) || 
            !wp_verify_nonce($_POST['avis_meta_nonce'], 'save_avis_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Sauvegarder les champs
        if (isset($_POST['avis_email'])) {
            update_post_meta($post_id, '_avis_email', sanitize_email($_POST['avis_email']));
        }
        
        if (isset($_POST['avis_rating'])) {
            update_post_meta($post_id, '_avis_rating', intval($_POST['avis_rating']));
        }
        
        if (isset($_POST['avis_product_id'])) {
            update_post_meta($post_id, '_avis_product_id', intval($_POST['avis_product_id']));
        }
        
        if (isset($_POST['avis_user_id'])) {
            update_post_meta($post_id, '_avis_user_id', intval($_POST['avis_user_id']));
        }
    }
}