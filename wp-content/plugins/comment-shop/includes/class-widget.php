<?php
/**
 * Widget pour afficher un témoignage aléatoire
 * 
 * @package CommentShop
 */

if (!defined('ABSPATH')) {
    exit;
}

class CS_Widget {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // HOOK : Ajouter le widget automatiquement dans le footer
        add_action('wp_footer', array($this, 'afficher_temoignage_footer'));
    }
    
    /**
     * HOOK : Afficher un témoignage aléatoire dans le footer du site
     */
    public function afficher_temoignage_footer() {
        // Ne pas afficher dans l'admin
        if (is_admin()) {
            return;
        }
        
        // Récupérer un avis aléatoire avec une bonne note
        $args = array(
            'post_type' => 'avis',
            'posts_per_page' => 1,
            'orderby' => 'rand',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_avis_rating',
                    'value' => 4,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                )
            )
        );
        
        $avis = get_posts($args);
        
        if (empty($avis)) {
            return;
        }
        
        $avis_item = $avis[0];
        $rating = get_post_meta($avis_item->ID, '_avis_rating', true);
        $email = get_post_meta($avis_item->ID, '_avis_email', true);
        
        // Extraire le prénom de l'email (avant le @)
        $nom_client = explode('@', $email)[0];
        $nom_client = ucfirst($nom_client);
        
        ?>
        <div class="cs-temoignage-footer" style="
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
        ">
            <div class="container" style="max-width: 800px; margin: 0 auto;">
                <h3 style="color: white; margin: 0 0 15px 0; font-size: 18px; text-transform: uppercase; letter-spacing: 2px;">
                    💬 Ce que disent nos clients
                </h3>
                
                <div class="temoignage-stars" style="margin-bottom: 15px; font-size: 24px;">
                    <?php echo str_repeat('⭐', $rating); ?>
                </div>
                
                <blockquote style="
                    font-size: 20px;
                    font-style: italic;
                    line-height: 1.6;
                    margin: 0 0 15px 0;
                    padding: 0 20px;
                    border: none;
                ">
                    "<?php echo esc_html(wp_trim_words($avis_item->post_content, 30)); ?>"
                </blockquote>
                
                <p style="
                    margin: 0;
                    font-weight: bold;
                    opacity: 0.9;
                    font-size: 16px;
                ">
                    — <?php echo esc_html($nom_client); ?>
                </p>
                
                <?php if (count(get_posts(array('post_type' => 'avis', 'posts_per_page' => -1))) > 1) : ?>
                <p style="margin: 20px 0 0 0; font-size: 14px; opacity: 0.8;">
                    <a href="<?php echo home_url('/temoignages'); ?>" style="color: white; text-decoration: underline;">
                        Voir tous les témoignages →
                    </a>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
