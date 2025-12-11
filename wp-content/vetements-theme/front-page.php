<h2>Nouveautés</h2>

<div class="nouveautes-grid">
    <?php
    $query = new WP_Query(array(
        'post_type' => 'nouveaute',
        'posts_per_page' => 6
    ));

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            
            <div class="nouveaute-card">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>

                <h3><?php the_title(); ?></h3>
                <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            </div>

        <?php endwhile;
    else :
        echo "<p>Aucune nouveauté pour le moment.</p>";
    endif;

    wp_reset_postdata();
    ?>
</div>