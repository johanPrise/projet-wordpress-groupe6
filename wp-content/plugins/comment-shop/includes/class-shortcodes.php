<?php
/**
 * Classe pour gérer les shortcodes
 * Permet d'afficher les avis n'importe où avec [avis_produit id="123"]
 */

if (!defined('ABSPATH')) {
    exit;
}

class CS_Shortcodes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Enregistrer les shortcodes
        add_shortcode('derniers_avis', array($this, 'afficher_derniers_avis'));
        add_shortcode('note_moyenne', array($this, 'afficher_note_moyenne'));
    }
    
    /**
     * SHORTCODE 1 : [derniers_avis]
     * Affiche les derniers témoignages clients
     */
    public function afficher_derniers_avis($atts) {
        $atts = shortcode_atts(array(
            'limit' => 5
        ), $atts);
        
        $limit = intval($atts['limit']);
        
        // Récupérer les derniers avis
        $avis = get_posts(array(
            'post_type' => 'avis',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        if (empty($avis)) {
            return '<p>Aucun avis disponible.</p>';
        }
        
        $output = '<div class="derniers-avis-shortcode">';
        $output .= '<h3>💬 Nos clients témoignent</h3>';
        
        foreach ($avis as $avis_item) {
            $rating = get_post_meta($avis_item->ID, '_avis_rating', true);
            $email = get_post_meta($avis_item->ID, '_avis_email', true);
            
            // Extraire prénom de l'email
            $nom_client = explode('@', $email)[0];
            $nom_client = ucfirst($nom_client);
            
            $output .= '<div class="avis-mini" style="border-left: 3px solid #FFD700; padding-left: 15px; margin-bottom: 20px;">';
            
            // Nom + Note
            $output .= '<p style="margin: 0 0 5px 0;"><strong>' . esc_html($nom_client) . '</strong> ';
            for ($i = 1; $i <= 5; $i++) {
                $output .= ($i <= $rating) ? '⭐' : '☆';
            }
            $output .= '</p>';
            
            // Témoignage
            $output .= '<p style="margin: 0; color: #555; font-style: italic;">"' . wp_trim_words($avis_item->post_content, 25) . '"</p>';
            
            // Date
            $output .= '<p style="margin: 5px 0 0 0; color: #999; font-size: 0.85em;">' . get_the_date('d/m/Y', $avis_item->ID) . '</p>';
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * SHORTCODE 2 : [note_moyenne]
     * Affiche la note moyenne globale de la boutique
     */
    public function afficher_note_moyenne($atts) {
        // Récupérer TOUS les témoignages
        $avis = get_posts(array(
            'post_type' => 'avis',
            'posts_per_page' => -1
        ));
        
        if (empty($avis)) {
            return '<span class="note-moyenne">Aucun témoignage</span>';
        }
        
        // Calculer moyenne globale
        $total = 0;
        foreach ($avis as $avis_item) {
            $rating = get_post_meta($avis_item->ID, '_avis_rating', true);
            $total += intval($rating);
        }
        $moyenne = round($total / count($avis), 1);
        
        // Afficher
        $output = '<div class="note-moyenne" style="display: inline-flex; align-items: center; gap: 10px; background: #f8f9fa; padding: 15px 20px; border-radius: 8px;">';
        $output .= '<span style="font-size: 28px; font-weight: bold; color: #FFD700;">' . $moyenne . '/5</span>';
        $output .= '<span>';
        for ($i = 1; $i <= 5; $i++) {
            $output .= ($i <= round($moyenne)) ? '<span style="color: #FFD700; font-size: 20px;">⭐</span>' : '<span style="color: #ddd; font-size: 20px;">☆</span>';
        }
        $output .= '</span>';
        $output .= '<span style="color: #666;">Basé sur ' . count($avis) . ' témoignages</span>';
        $output .= '</div>';
        
        return $output;
    }
}