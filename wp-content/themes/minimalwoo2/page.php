<?php
/**
 * Template pour les pages statiques
 */

get_header(); ?>

<div class="container">
    <div class="content-area page-content">
        <main class="site-main">
            <?php
            while (have_posts()) : the_post(); ?>
                <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="page-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>

                <?php
                // Commentaires sur les pages (si activés)
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
