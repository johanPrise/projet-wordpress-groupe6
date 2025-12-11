<?php
/**
 * Template pour les archives (catégories, dates, auteurs)
 * Template Hierarchy: archive.php
 */

get_header(); ?>

<div class="container">
    <div class="content-area archive-page">
        <header class="page-header">
            <?php
            the_archive_title('<h1 class="archive-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <main class="site-main">
            <?php if (have_posts()) : ?>
                <div class="posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="post-content">
                                <header class="entry-header">
                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="entry-meta">
                                        <span class="date">📅 <?php echo get_the_date(); ?></span>
                                        <span class="author">✍️ <?php the_author(); ?></span>
                                    </div>
                                </header>

                                <div class="entry-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Lire la suite →
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                // Pagination
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← Précédent',
                    'next_text' => 'Suivant →',
                ));
                ?>

            <?php else : ?>
                <div class="no-posts">
                    <p>Aucun article trouvé dans cette archive.</p>
                </div>
            <?php endif; ?>
        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
