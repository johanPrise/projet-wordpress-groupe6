<?php
/**
 * Template Name: Page d'accueil
 * Description: Template personnalisé pour la page d'accueil
 */

get_header(); ?>

<main class="homepage">
    
    <!-- Section Hero / Bannière -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Bienvenue chez <?php bloginfo('name'); ?></h1>
                <p class="hero-description">Découvrez notre collection de vêtements tendance</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="hero-btn">Voir la boutique</a>
            </div>
        </div>
    </section>

    <!-- Section Présentation -->
    <section class="about-section">
        <div class="container">
            <h2>Notre Boutique</h2>
            <p>Des vêtements de qualité pour tous les styles. Découvrez nos dernières collections et trouvez votre look idéal.</p>
        </div>
    </section>

    <!-- Section Produits mis en avant -->
    <section class="featured-products">
        <div class="container">
            <h2>Nos Produits Phares</h2>
            
            <?php
            // Afficher les produits en vedette WooCommerce
            if (class_exists('WooCommerce')) {
                echo do_shortcode('[products limit="6" columns="3" orderby="popularity"]');
            }
            ?>
        </div>
    </section>

    <!-- Section Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2>Prêt à faire du shopping ?</h2>
            <p>Parcourez notre catalogue complet et trouvez vos coups de cœur</p>
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="cta-btn">Découvrir tous les produits</a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
