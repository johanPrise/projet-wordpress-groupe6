function wpt_create_nouveautes_cpt() {

    $labels = array(
        'name' => 'Nouveautés',
        'singular_name' => 'Nouveauté',
        'menu_name' => 'Nouveautés',
        'add_new' => 'Ajouter une nouveauté',
        'add_new_item' => 'Ajouter une nouvelle nouveauté',
        'edit_item' => 'Modifier la nouveauté',
        'new_item' => 'Nouvelle nouveauté',
        'view_item' => 'Voir la nouveauté',
        'search_items' => 'Rechercher une nouveauté',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true // pour Gutenberg
    );

    register_post_type('nouveaute', $args);
}
add_action('init', 'wpt_create_nouveautes_cpt');