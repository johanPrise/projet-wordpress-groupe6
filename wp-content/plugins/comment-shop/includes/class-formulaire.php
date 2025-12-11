<?php
/**
 * Classe pour gérer le formulaire de soumission d'avis
 * Gère l'affichage et le traitement AJAX du formulaire
 */

if (!defined('ABSPATH')) {
    exit;
}

class CS_Formulaire {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Shortcode pour afficher le formulaire
        add_shortcode('formulaire_avis', array($this, 'afficher_formulaire'));
        
        // Actions AJAX pour soumettre un avis
        add_action('wp_ajax_submit_avis', array($this, 'traiter_soumission_ajax'));
        add_action('wp_ajax_nopriv_submit_avis', array($this, 'traiter_soumission_ajax'));
        
        // Action standard (sans AJAX)
        add_action('admin_post_submit_avis_client', array($this, 'traiter_soumission'));
        add_action('admin_post_nopriv_submit_avis_client', array($this, 'traiter_soumission'));
    }
    
    /**
     * Afficher le formulaire via shortcode
     */
    public function afficher_formulaire($atts) {
        $atts = shortcode_atts(array(
            'product_id' => 0
        ), $atts);
        
        $product_id = intval($atts['product_id']);
        
        ob_start();
        ?>
        
        <div class="formulaire-avis-wrapper" id="formulaire-avis">
            <h3>✍️ Laissez votre avis</h3>
            
            <?php if (isset($_GET['avis_success'])) : ?>
                <div class="notice notice-success" style="padding: 15px; background: #d4edda; border-left: 4px solid #28a745; margin-bottom: 20px;">
                    <p><strong>Merci !</strong> Votre avis a été soumis avec succès.</p>
                </div>
            <?php endif; ?>
            
            <form id="form-avis-ajax" class="avis-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                
                <input type="hidden" name="action" value="submit_avis_client">
                <?php wp_nonce_field('submit_avis_nonce', 'avis_nonce'); ?>
                
                <?php if ($product_id > 0) : ?>
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <?php endif; ?>
                
                <!-- Nom -->
                <p>
                    <label for="client_name">Votre nom <span style="color: red;">*</span></label>
                    <input type="text" 
                           id="client_name" 
                           name="client_name" 
                           required 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </p>
                
                <!-- Email -->
                <p>
                    <label for="client_email">Votre email <span style="color: red;">*</span></label>
                    <input type="email" 
                           id="client_email" 
                           name="client_email" 
                           required 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small>Votre email ne sera pas publié</small>
                </p>
                
                <!-- Produit (si non spécifié) -->
                <?php if ($product_id == 0) : ?>
                <p>
                    <label for="product_select">Produit concerné</label>
                    <select id="product_select" 
                            name="product_id" 
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">-- Sélectionner (optionnel) --</option>
                        <?php
                        $products = wc_get_products(array('limit' => -1));
                        foreach ($products as $prod) :
                        ?>
                            <option value="<?php echo $prod->get_id(); ?>">
                                <?php echo $prod->get_name(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php endif; ?>
                
                <!-- Note -->
                <p>
                    <label>Votre note <span style="color: red;">*</span></label>
                    <div class="rating-input" style="display: flex; gap: 5px; font-size: 30px;">
                        <?php for ($i = 5; $i >= 1; $i--) : ?>
                            <label style="cursor: pointer;">
                                <input type="radio" name="rating" value="<?php echo $i; ?>" required style="display: none;">
                                <span class="star-select" data-value="<?php echo $i; ?>" style="color: #ddd;">★</span>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <small id="rating-text" style="display: block; margin-top: 5px;">Cliquez pour noter</small>
                </p>
                
                <!-- Commentaire -->
                <p>
                    <label for="avis_comment">Votre avis <span style="color: red;">*</span></label>
                    <textarea id="avis_comment" 
                              name="avis_comment" 
                              rows="5" 
                              required 
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                              placeholder="Partagez votre expérience..."></textarea>
                </p>
                
                <!-- Bouton -->
                <p>
                    <button type="submit" 
                            class="button button-primary" 
                            style="background: #667eea; color: #fff; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                        📤 Envoyer mon avis
                    </button>
                </p>
                
            </form>
        </div>
        
        <style>
        .star-select:hover,
        .star-select.active {
            color: #FFD700 !important;
        }
        </style>
        
        <?php
        return ob_get_clean();
    }
    
    /**
     * Traiter la soumission AJAX (BONUS)
     */
    public function traiter_soumission_ajax() {
        // Vérifier le nonce
        check_ajax_referer('cs_ajax_nonce', 'nonce');
        
        // Récupérer et nettoyer les données
        $client_name = sanitize_text_field($_POST['client_name']);
        $client_email = sanitize_email($_POST['client_email']);
        $rating = intval($_POST['rating']);
        $comment = sanitize_textarea_field($_POST['avis_comment']);
        $product_id = intval($_POST['product_id']);
        
        // Créer l'avis
        $avis_id = wp_insert_post(array(
            'post_type' => 'avis',
            'post_title' => $client_name,
            'post_content' => $comment,
            'post_status' => 'publish'
        ));
        
        if ($avis_id) {
            // Ajouter les métadonnées
            update_post_meta($avis_id, '_avis_email', $client_email);
            update_post_meta($avis_id, '_avis_rating', $rating);
            update_post_meta($avis_id, '_avis_product_id', $product_id);
            
            wp_send_json_success(array(
                'message' => 'Avis soumis avec succès !'
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Erreur lors de la soumission'
            ));
        }
    }
    
    /**
     * Traiter la soumission standard (sans AJAX)
     */
    public function traiter_soumission() {
        // Vérifier le nonce
        if (!isset($_POST['avis_nonce']) || !wp_verify_nonce($_POST['avis_nonce'], 'submit_avis_nonce')) {
            wp_die('Erreur de sécurité');
        }
        
        // Récupérer les données
        $client_name = sanitize_text_field($_POST['client_name']);
        $client_email = sanitize_email($_POST['client_email']);
        $rating = intval($_POST['rating']);
        $comment = sanitize_textarea_field($_POST['avis_comment']);
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        // Créer l'avis
        $avis_id = wp_insert_post(array(
            'post_type' => 'avis',
            'post_title' => $client_name,
            'post_content' => $comment,
            'post_status' => 'publish',
            'post_author' => get_current_user_id()
        ));
        
        if ($avis_id) {
            // Ajouter les métadonnées
            update_post_meta($avis_id, '_avis_email', $client_email);
            update_post_meta($avis_id, '_avis_rating', $rating);
            if ($product_id > 0) {
                update_post_meta($avis_id, '_avis_product_id', $product_id);
            }
            
            // Rediriger avec message de succès
            wp_redirect(add_query_arg('avis_success', '1', wp_get_referer()));
            exit;
        } else {
            wp_die('Erreur lors de la création de l\'avis');
        }
    }
}
