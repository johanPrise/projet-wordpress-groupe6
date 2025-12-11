# Documentation Technique du Projet WordPress
## Boutique WooCommerce - MinimalWoo

---

**Projet réalisé dans le cadre du TP WordPress**
**Date de rendu :** Décembre 2025

---

## Table des Matières

1. [Concept du Site](#1-concept-du-site)
2. [Architecture du Thème](#2-architecture-du-thème)
3. [Architecture de l'Extension](#3-architecture-de-lextension)
4. [Hooks Utilisés](#4-hooks-utilisés)
5. [Structure des Templates](#5-structure-des-templates)
6. [Personnalisations WooCommerce](#6-personnalisations-woocommerce)
7. [Fonctionnalités Bonus](#7-fonctionnalités-bonus)

---

## 1. Concept du Site

### 1.1 Présentation Générale

Notre projet est une **boutique en ligne e-commerce** construite avec WordPress et WooCommerce. Le site adopte une approche minimaliste et moderne, privilégiant l'expérience utilisateur et la clarté de navigation.

L'objectif principal est de proposer une plateforme de vente en ligne complète avec :
- Un catalogue de produits attractif
- Un système de témoignages clients intégré
- Une wishlist pour les utilisateurs
- Un processus d'achat fluide

### 1.2 Identité Visuelle

Le design repose sur une palette de couleurs cohérente définie via des variables CSS :

| Couleur | Code Hex | Utilisation |
|---------|----------|-------------|
| Primaire | `#2c3e50` | Textes principaux, navigation |
| Secondaire | `#e74c3c` | Boutons d'action, alertes |
| Accent | `#3498db` | Liens, éléments interactifs |
| Succès | `#27ae60` | Confirmations, badges stock |

La typographie utilise une police sans-serif moderne pour une lecture optimale sur tous les supports. Le container principal est limité à 1200px pour une lisibilité optimale.

### 1.3 Pages du Site

Le site comprend les pages essentielles suivantes :

1. **Page d'Accueil** (`front-page.php`) - Hero section avec produits mis en avant
2. **Boutique** (`archive-product.php`) - Catalogue complet des produits WooCommerce
3. **Pages Produits** (`single-product.php`) - Fiches détaillées avec galerie et options
4. **Panier** - Gestion du panier d'achat
5. **Validation de Commande** - Processus de paiement
6. **Témoignages** (`/temoignages/`) - Archive des avis clients (CPT personnalisé)
7. **Contact / À propos** - Informations de contact

---

## 2. Architecture du Thème

### 2.1 Informations Générales

| Propriété | Valeur |
|-----------|--------|
| **Nom du thème** | minimalwoo2 |
| **Emplacement** | `/wp-content/themes/minimalwoo2/` |
| **Compatibilité** | WordPress 6.x, WooCommerce 8.x |
| **Responsive** | Oui (mobile-first) |

### 2.2 Structure des Fichiers

```
minimalwoo2/
├── style.css                 # Feuille de style principale + métadonnées
├── functions.php             # Configuration et hooks du thème
├── index.php                 # Template de fallback
├── header.php                # En-tête du site
├── footer.php                # Pied de page
├── sidebar.php               # Barre latérale
├── front-page.php            # Page d'accueil personnalisée
├── single.php                # Articles individuels
├── archive.php               # Archives (catégories, tags)
├── page.php                  # Pages statiques
├── css/
│   ├── variables.css         # Variables CSS et reset
│   ├── header.css            # Styles navigation
│   ├── footer.css            # Styles pied de page
│   ├── home.css              # Styles page d'accueil
│   ├── woocommerce.css       # Styles boutique
│   └── responsive.css        # Media queries
├── js/
│   └── main.js               # Scripts JavaScript
└── woocommerce/
    ├── archive-product.php   # Page boutique personnalisée
    ├── single-product.php    # Page produit personnalisée
    └── content-single-product.php
```

### 2.3 Configuration dans functions.php

Le fichier `functions.php` configure les fonctionnalités essentielles du thème :

**Support des fonctionnalités WordPress :**
```php
// Images à la une
add_theme_support('post-thumbnails');

// Balise title automatique
add_theme_support('title-tag');

// Markup HTML5 sémantique
add_theme_support('html5', array(
    'search-form', 'comment-form', 'comment-list',
    'gallery', 'caption'
));

// Logo personnalisé
add_theme_support('custom-logo');
```

**Support WooCommerce complet :**
```php
add_theme_support('woocommerce');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
```

**Menus et Widgets :**
```php
// Menu de navigation principal
register_nav_menus(array(
    'main-menu' => 'Menu Principal'
));

// Zone de widgets sidebar
register_sidebar(array(
    'name'          => 'Sidebar',
    'id'            => 'sidebar-1',
    'before_widget' => '<div class="widget">',
    'after_widget'  => '</div>'
));
```

### 2.4 Organisation CSS Modulaire

Le thème utilise une architecture CSS modulaire via des imports dans `style.css` :

```css
@import url('css/variables.css');    /* Variables globales et reset */
@import url('css/header.css');       /* Navigation et header */
@import url('css/footer.css');       /* Footer */
@import url('css/home.css');         /* Page d'accueil */
@import url('css/woocommerce.css');  /* Styles produits */
@import url('css/responsive.css');   /* Responsive design */
```

### 2.5 Design Responsive

Le site est entièrement responsive avec des breakpoints définis :

| Breakpoint | Largeur | Comportement |
|------------|---------|--------------|
| Desktop | > 1024px | Grille 3 colonnes, navigation horizontale |
| Tablette | 768px - 1024px | Grille 2 colonnes |
| Mobile | < 768px | 1 colonne, menu hamburger |

---

## 3. Architecture de l'Extension

### 3.1 Extension Principale : Comment-Shop

| Propriété | Valeur |
|-----------|--------|
| **Nom** | Comment-Shop (Système de Témoignages) |
| **Version** | 1.0.0 |
| **Auteur** | Johan PRISO |
| **Emplacement** | `/wp-content/plugins/comment-shop/` |

### 3.2 Structure des Fichiers

```
comment-shop/
├── comment-shop.php          # Bootstrap principal du plugin
├── includes/
│   ├── class-cpt-avis.php    # Définition du Custom Post Type
│   ├── class-metaboxes.php   # Métaboxes admin pour les champs
│   ├── class-formulaire.php  # Formulaire frontend + AJAX
│   ├── class-shortcodes.php  # Shortcodes disponibles
│   └── class-widget.php      # Widget footer automatique
└── assets/
    ├── css/
    │   └── avis-styles.css   # Styles frontend
    └── js/
        └── avis-ajax.js      # Interactivité étoiles + AJAX
```

### 3.3 Custom Post Type : Témoignages (avis)

L'extension crée un CPT "avis" pour gérer les témoignages clients :

```php
register_post_type('avis', array(
    'labels' => array(
        'name'          => 'Témoignages',
        'singular_name' => 'Témoignage',
        'add_new'       => 'Ajouter un témoignage',
        'edit_item'     => 'Modifier le témoignage',
        'view_item'     => 'Voir le témoignage'
    ),
    'public'       => true,
    'has_archive'  => true,
    'rewrite'      => array('slug' => 'temoignages'),
    'menu_icon'    => 'dashicons-star-filled',
    'supports'     => array('title', 'editor', 'author'),
    'show_in_rest' => true  // Support Gutenberg
));
```

**URL de l'archive :** `/temoignages/`
**URL individuelle :** `/temoignages/[nom-du-post]/`

### 3.4 Champs Personnalisés (Post Meta)

| Champ | Clé Meta | Type | Description |
|-------|----------|------|-------------|
| Email client | `_avis_email` | Email | Adresse email du client |
| Note | `_avis_rating` | Integer (1-5) | Évaluation en étoiles |
| Produit lié | `_avis_product_id` | Integer | ID produit WooCommerce (optionnel) |
| Utilisateur | `_avis_user_id` | Integer | ID utilisateur WordPress |

### 3.5 Shortcodes Disponibles

#### `[derniers_avis]`
Affiche les derniers témoignages publiés sous forme de liste.

**Paramètres :**
- `limit` (défaut: 5) - Nombre de témoignages à afficher

**Exemple d'utilisation :**
```
[derniers_avis limit="3"]
```

#### `[note_moyenne]`
Calcule et affiche la note moyenne de tous les témoignages.

**Sortie :** "4.5/5 - Basé sur 25 témoignages"

#### `[formulaire_avis]`
Affiche un formulaire interactif de soumission de témoignage.

**Paramètres :**
- `product_id` (optionnel) - Pré-sélectionne un produit

**Fonctionnalités :**
- Système d'étoiles interactif (hover + click)
- Validation côté client et serveur
- Soumission AJAX sans rechargement de page
- Messages de succès/erreur

### 3.6 Pattern Singleton

Chaque classe utilise le pattern Singleton pour garantir une seule instance :

```php
class CS_CPT_Avis {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Initialisation
    }
}
```

### 3.7 Extension Secondaire : Wishlist

Une extension wishlist permet la sauvegarde de produits favoris :

| Fonctionnalité | Description |
|----------------|-------------|
| Stockage | Session PHP (`$_SESSION['wishlist']`) |
| Shortcode | `[wishlist]` - Affiche la liste |
| Bouton | Ajouté automatiquement sur les pages produit |
| CPT bonus | "nouveaute" pour les nouveautés |

---

## 4. Hooks Utilisés

### 4.1 Hooks dans le Thème (2 hooks)

#### Hook 1 : Badges Produit Personnalisés

| Propriété | Valeur |
|-----------|--------|
| **Type** | Action |
| **Hook** | `woocommerce_single_product_summary` |
| **Priorité** | 6 (avant le titre) |
| **Fichier** | `functions.php` |

```php
add_action('woocommerce_single_product_summary',
    'simpliste_custom_after_product_title', 6);

function simpliste_custom_after_product_title() {
    global $product;

    // Badge promotion
    if ($product->is_on_sale()) {
        echo '<span class="badge badge-sale">En Promotion</span>';
    }

    // Badge rupture de stock
    if (!$product->is_in_stock()) {
        echo '<span class="badge badge-outstock">Rupture de stock</span>';
    }
}
```

**Explication :** Ce hook s'exécute avant le titre du produit et ajoute des badges visuels colorés pour indiquer rapidement l'état du produit (en promotion ou en rupture de stock). Cela améliore l'UX en donnant une information visuelle immédiate.

---

#### Hook 2 : Texte Bouton "Ajouter au Panier"

| Propriété | Valeur |
|-----------|--------|
| **Type** | Filtre |
| **Hook** | `woocommerce_product_single_add_to_cart_text` |
| **Fichier** | `functions.php` |

```php
add_filter('woocommerce_product_single_add_to_cart_text',
    'simpliste_custom_add_to_cart_text');

function simpliste_custom_add_to_cart_text($text) {
    global $product;

    switch ($product->get_type()) {
        case 'simple':
            return 'Ajouter à mon panier';
        case 'variable':
            return 'Choisir les options';
        case 'grouped':
            return 'Voir les produits';
        default:
            return $text;
    }
}
```

**Explication :** Ce filtre personnalise le texte du bouton d'ajout au panier selon le type de produit WooCommerce, rendant l'interface plus claire et intuitive pour l'utilisateur français.

---

### 4.2 Hook dans l'Extension (1 hook)

#### Hook : Widget Témoignage en Footer

| Propriété | Valeur |
|-----------|--------|
| **Type** | Action |
| **Hook** | `get_footer` |
| **Fichier** | `includes/class-widget.php` |

```php
add_action('get_footer', array($this, 'display_footer_widget'));

public function display_footer_widget() {
    // Ne pas afficher en admin
    if (is_admin()) return;

    // Récupère un témoignage aléatoire avec note >= 4
    $avis = get_posts(array(
        'post_type'      => 'avis',
        'posts_per_page' => 1,
        'orderby'        => 'rand',
        'meta_query'     => array(
            array(
                'key'     => '_avis_rating',
                'value'   => 4,
                'compare' => '>=',
                'type'    => 'NUMERIC'
            )
        )
    ));

    if (!empty($avis)) {
        $this->render_widget($avis[0]);
    }
}
```

**Explication :** Ce hook injecte automatiquement un témoignage client positif (note supérieure ou égale à 4/5) dans une bannière colorée juste avant le footer de chaque page. Cette technique de "social proof" renforce la confiance des visiteurs en affichant des avis authentiques.

---

### 4.3 Tableau Récapitulatif des Hooks

| Hook | Type | Emplacement | Fonction |
|------|------|-------------|----------|
| `woocommerce_single_product_summary` | Action | Thème | Badges produit |
| `woocommerce_product_single_add_to_cart_text` | Filtre | Thème | Texte bouton panier |
| `get_footer` | Action | Extension | Widget témoignage |
| `wp_ajax_submit_avis` | Action | Extension | Soumission AJAX |
| `save_post_avis` | Action | Extension | Sauvegarde métadonnées |
| `loop_shop_per_page` | Filtre | Thème | 12 produits/page |
| `loop_shop_columns` | Filtre | Thème | Grille 3 colonnes |

---

## 5. Structure des Templates

### 5.1 Schéma de la Hiérarchie des Templates

```
                    ┌─────────────────────────────────────┐
                    │           REQUÊTE WORDPRESS          │
                    └─────────────────┬───────────────────┘
                                      │
        ┌─────────────────────────────┼─────────────────────────────┐
        │                             │                             │
        ▼                             ▼                             ▼
┌───────────────┐           ┌───────────────┐           ┌───────────────┐
│  Page Accueil │           │    Article    │           │   Archive     │
│               │           │               │           │               │
│ front-page.php│           │  single.php   │           │ archive.php   │
└───────────────┘           └───────────────┘           └───────────────┘
                                      │
                    ┌─────────────────┼─────────────────┐
                    │                 │                 │
                    ▼                 ▼                 ▼
            ┌─────────────┐   ┌─────────────┐   ┌─────────────┐
            │   Produit   │   │   Boutique  │   │    Page     │
            │   unique    │   │   (archive) │   │  statique   │
            │             │   │             │   │             │
            │single-      │   │archive-     │   │  page.php   │
            │product.php  │   │product.php  │   │             │
            └─────────────┘   └─────────────┘   └─────────────┘
```

### 5.2 Templates WordPress Standards

| Template | Utilisation | Caractéristiques |
|----------|-------------|------------------|
| `index.php` | Fallback universel | Loop basique avec titre et extrait |
| `single.php` | Articles de blog | Meta (date, auteur), navigation, commentaires |
| `archive.php` | Archives (catégories, tags, dates) | Grille de cartes, pagination |
| `page.php` | Pages statiques | Pleine largeur, support thumbnail |
| `front-page.php` | Page d'accueil | Hero, à propos, produits vedettes |

### 5.3 Structure de front-page.php (Page d'Accueil)

```
┌────────────────────────────────────────────────────────┐
│                      HEADER                             │
│  Logo | Menu Principal | Icône Panier                  │
├────────────────────────────────────────────────────────┤
│                                                        │
│    ╔════════════════════════════════════════════╗     │
│    ║           SECTION HERO                      ║     │
│    ║   • Titre principal accrocheur              ║     │
│    ║   • Sous-titre descriptif                   ║     │
│    ║   • Bouton CTA "Découvrir la boutique"     ║     │
│    ╚════════════════════════════════════════════╝     │
│                                                        │
│    ╔════════════════════════════════════════════╗     │
│    ║           SECTION À PROPOS                  ║     │
│    ║   • Description de la boutique              ║     │
│    ║   • Valeurs et engagements                  ║     │
│    ╚════════════════════════════════════════════╝     │
│                                                        │
│    ╔════════════════════════════════════════════╗     │
│    ║         PRODUITS EN VEDETTE                 ║     │
│    ║   Shortcode: [products limit="8" cols="4"]  ║     │
│    ║   ┌────┐ ┌────┐ ┌────┐ ┌────┐             ║     │
│    ║   │Prod│ │Prod│ │Prod│ │Prod│             ║     │
│    ║   └────┘ └────┘ └────┘ └────┘             ║     │
│    ╚════════════════════════════════════════════╝     │
│                                                        │
│    ╔════════════════════════════════════════════╗     │
│    ║         WIDGET TÉMOIGNAGE (via hook)        ║     │
│    ╚════════════════════════════════════════════╝     │
│                                                        │
├────────────────────────────────────────────────────────┤
│                      FOOTER                             │
│  Copyright | Mentions légales                          │
└────────────────────────────────────────────────────────┘
```

### 5.4 Templates WooCommerce Personnalisés

#### woocommerce/single-product.php (Page Produit)

```
┌────────────────────────────────────────────────────────┐
│                      HEADER                             │
├────────────────────────────────────────────────────────┤
│                                                        │
│  ┌────────────────────┬─────────────────────────────┐ │
│  │                    │                             │ │
│  │    GALERIE         │      RÉSUMÉ PRODUIT        │ │
│  │    IMAGES          │                             │ │
│  │                    │  • Badges (promo/stock)     │ │
│  │  ┌──────────────┐  │  • Titre du produit         │ │
│  │  │              │  │  • Prix (barré si promo)    │ │
│  │  │    Image     │  │  • Note moyenne étoiles     │ │
│  │  │  principale  │  │  • Description courte       │ │
│  │  │              │  │  • Sélecteur quantité       │ │
│  │  └──────────────┘  │  • Bouton "Ajouter au       │ │
│  │                    │     panier"                  │ │
│  │  [miniatures]      │  • Catégories et tags       │ │
│  │                    │                             │ │
│  └────────────────────┴─────────────────────────────┘ │
│                                                        │
│  ╔═══════════════════════════════════════════════════╗│
│  ║              ONGLETS PRODUIT                       ║│
│  ║  Description | Informations | Avis clients         ║│
│  ╚═══════════════════════════════════════════════════╝│
│                                                        │
│  ╔═══════════════════════════════════════════════════╗│
│  ║           PRODUITS SIMILAIRES                      ║│
│  ║   ┌────┐ ┌────┐ ┌────┐ ┌────┐                    ║│
│  ║   │    │ │    │ │    │ │    │                    ║│
│  ║   └────┘ └────┘ └────┘ └────┘                    ║│
│  ╚═══════════════════════════════════════════════════╝│
│                                                        │
├────────────────────────────────────────────────────────┤
│                      FOOTER                             │
└────────────────────────────────────────────────────────┘
```

---

## 6. Personnalisations WooCommerce

### 6.1 Support Complet WooCommerce

Le thème déclare le support WooCommerce avec toutes les fonctionnalités modernes de galerie :

```php
// Support de base
add_theme_support('woocommerce');

// Fonctionnalités galerie produit
add_theme_support('wc-product-gallery-zoom');      // Zoom au survol
add_theme_support('wc-product-gallery-lightbox');  // Lightbox plein écran
add_theme_support('wc-product-gallery-slider');    // Slider miniatures
```

### 6.2 Personnalisation de l'Affichage Boutique

```php
// Afficher 12 produits par page (au lieu de 9 par défaut)
add_filter('loop_shop_per_page', function() {
    return 12;
});

// Afficher en grille de 3 colonnes
add_filter('loop_shop_columns', function() {
    return 3;
});
```

### 6.3 Templates Surchargés

| Template Original | Template Surchargé | Personnalisation |
|-------------------|-------------------|------------------|
| `archive-product.php` | `woocommerce/archive-product.php` | Layout avec sidebar optionnelle |
| `single-product.php` | `woocommerce/single-product.php` | Design 2 colonnes amélioré |
| `content-single-product.php` | `woocommerce/content-single-product.php` | Intégration hooks personnalisés |

### 6.4 Styles CSS Personnalisés

La boutique utilise CSS Grid pour un affichage moderne et responsive :

```css
/* Grille de produits */
.products {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

/* Carte produit */
.product-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

/* Image produit */
.product-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}
```

### 6.5 Panier et Paiement

| Fonctionnalité | Statut | Description |
|----------------|--------|-------------|
| Icône panier header | Actif | Affiche le nombre d'articles |
| Page panier | Fonctionnelle | Mise à jour quantités, suppression |
| Page paiement | Fonctionnelle | Formulaire de commande complet |
| Mode de paiement | Configuré | Paiement à la livraison |

---

## 7. Fonctionnalités Bonus

### 7.1 API WordPress AJAX

L'extension Comment-Shop implémente une soumission AJAX complète pour les témoignages :

**Côté Backend (PHP) :**
```php
// Enregistrement des handlers AJAX
add_action('wp_ajax_submit_avis', array($this, 'handle_ajax_submission'));
add_action('wp_ajax_nopriv_submit_avis', array($this, 'handle_ajax_submission'));

public function handle_ajax_submission() {
    // Vérification du nonce (sécurité CSRF)
    check_ajax_referer('cs_ajax_nonce', 'nonce');

    // Sanitization des données
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $rating = intval($_POST['rating']);
    $comment = sanitize_textarea_field($_POST['comment']);

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        wp_send_json_error(array('message' => 'Email invalide'));
    }

    // Création du post
    $post_id = wp_insert_post(array(
        'post_type'   => 'avis',
        'post_title'  => $name,
        'post_content'=> $comment,
        'post_status' => 'pending'
    ));

    // Sauvegarde des métadonnées
    update_post_meta($post_id, '_avis_email', $email);
    update_post_meta($post_id, '_avis_rating', $rating);

    wp_send_json_success(array('message' => 'Merci pour votre témoignage !'));
}
```

**Côté Frontend (JavaScript) :**
```javascript
$('#avis-form').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: cs_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'submit_avis',
            nonce: cs_ajax.nonce,
            name: $('#name').val(),
            email: $('#email').val(),
            rating: $('input[name="rating"]:checked').val(),
            comment: $('#comment').val()
        },
        success: function(response) {
            if (response.success) {
                alert(response.data.message);
                $('#avis-form')[0].reset();
            }
        },
        error: function() {
            alert('Erreur lors de l\'envoi');
        }
    });
});
```

### 7.2 Sécurité Implémentée

| Mesure | Implémentation | Fichier |
|--------|----------------|---------|
| Protection CSRF | Nonces WordPress | Tous les formulaires |
| Sanitization | `sanitize_text_field()`, `sanitize_email()` | class-formulaire.php |
| Escaping | `esc_html()`, `esc_attr()`, `esc_url()` | Tous les templates |
| Validation | `filter_var()`, `intval()` | class-formulaire.php |
| Capabilities | `current_user_can('edit_post')` | class-metaboxes.php |

### 7.3 Respect des Standards WordPress

| Standard | Implémentation |
|----------|----------------|
| Préfixage fonctions | `simpliste_`, `cs_` |
| Text domain | Préparé pour i18n |
| Support Gutenberg | `show_in_rest => true` |
| Enqueue assets | `wp_enqueue_scripts` |
| Architecture OOP | Classes avec Singleton |

### 7.4 Système d'Étoiles Interactif

Le formulaire de témoignage inclut un système d'étoiles interactif :

```javascript
// Labels dynamiques selon la note
const labels = {
    1: 'Décevant',
    2: 'Moyen',
    3: 'Bien',
    4: 'Très bien',
    5: 'Excellent'
};

// Interaction hover
$('.star').hover(function() {
    const value = $(this).data('value');
    highlightStars(value);
    $('#rating-label').text(labels[value]);
});
```

---

## Conclusion

Ce projet WordPress démontre une maîtrise complète des concepts fondamentaux du développement WordPress :

**Thème Personnalisé :**
- Template hierarchy respectée (front-page, single, archive, page)
- Design responsive mobile-first
- Intégration WooCommerce complète
- 2 hooks fonctionnels documentés

**Extension Personnalisée :**
- Custom Post Type "avis" avec métadonnées
- 3 shortcodes utiles
- Soumission AJAX sécurisée
- Widget footer automatique via hook

**WooCommerce :**
- Templates surchargés et personnalisés
- Badges produit dynamiques
- Grille responsive CSS Grid
- Processus d'achat complet

**Bonnes Pratiques :**
- Sécurité (nonces, sanitization, escaping)
- Architecture modulaire et maintenable
- Code commenté et préfixé
- Standards WordPress respectés

Le site est entièrement fonctionnel, sécurisé et prêt pour une mise en production.

---

*Document technique rédigé pour le TP WordPress*
*Décembre 2025*
