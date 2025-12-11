<?php
/**
 * Template pour afficher un article individuel
 * Template Hierarchy: single.php
 */

get_header(); ?>

<div class="container">
    <div class="content-area single-post">
        <main class="site-main">
            <?php
            while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <div class="entry-meta">
                            <span class="posted-on">
                                📅 Publié le <?php echo get_the_date(); ?>
                            </span>
                            <span class="author">
                                ✍️ Par <?php the_author(); ?>
                            </span>
                            <?php if (has_category()) : ?>
                                <span class="categories">
                                    🏷️ <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <?php if (has_tag()) : ?>
                        <footer class="entry-footer">
                            <div class="tags">
                                <?php the_tags('🔖 Tags: ', ', ', ''); ?>
                            </div>
                        </footer>
                    <?php endif; ?>
                </article>

                <?php
                // Navigation entre articles
                the_post_navigation(array(
                    'prev_text' => '← Article précédent',
                    'next_text' => 'Article suivant →',
                ));

                // Commentaires
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; ?>
        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
