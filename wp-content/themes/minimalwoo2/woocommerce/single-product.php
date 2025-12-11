<?php
/**
 * Template pour la page produit individuel
 * WooCommerce: single-product.php
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<div class="container single-product-container">
    <?php
    /**
     * Hook: woocommerce_before_main_content
     */
    do_action('woocommerce_before_main_content');
    ?>

    <div class="product-wrapper">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <?php wc_get_template_part('content', 'single-product'); ?>

        <?php endwhile; ?>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_main_content
     */
    do_action('woocommerce_after_main_content');
    ?>
</div>

<?php
/**
 * Hook: woocommerce_sidebar
 */
do_action('woocommerce_sidebar');

get_footer('shop');
