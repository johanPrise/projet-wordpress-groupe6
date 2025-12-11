<?php
/**
 * Template pour le contenu d'un produit individuel
 * WooCommerce: content-single-product.php
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
    
    <div class="product-layout">
        <div class="product-images-section">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary
             * 
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action('woocommerce_before_single_product_summary');
            ?>
        </div>

        <div class="product-summary-section">
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary
                 * 
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked simpliste_custom_after_product_title - 6 (notre hook)
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked simpliste_free_shipping_notice - 25 (notre hook)
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 */
                do_action('woocommerce_single_product_summary');
                ?>
            </div>
        </div>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary
     * 
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');
    ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
