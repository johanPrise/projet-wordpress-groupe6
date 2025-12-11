# Comment Shop - Extension de Témoignages Clients

**Version:** 1.0.0  
**Auteur:** Johan PRISO  
**Type:** Plugin WordPress  
**Compatibilité:** WordPress 5.8+, WooCommerce 6.0+

---

## 📋 Description

**Comment Shop** est une extension WordPress permettant de gérer des **témoignages clients généraux** sur une boutique en ligne. Contrairement aux avis produits natifs de WooCommerce (qui sont liés à un produit spécifique), cette extension crée un système de témoignages **globaux** affichables partout sur le site (homepage, sidebar, footer).

### 🎯 Objectif Académique

Cette extension a été développée dans le cadre d'un projet universitaire démontrant la maîtrise de :
- Custom Post Types (CPT)
- Système de hooks WordPress
- Intégration WooCommerce
- AJAX et sécurité WordPress
- Architecture MVC-like

---

## 🏗️ Architecture Technique

### Structure des fichiers

```
comment-shop/
├── comment-shop.php          # Fichier principal (bootstrap)
├── README.md                 # Documentation
├── assets/
│   ├── css/
│   │   └── avis-styles.css   # Styles frontend
│   └── js/
│       └── avis-ajax.js      # JavaScript (étoiles + AJAX)
└── includes/
    ├── class-cpt-avis.php        # Custom Post Type "avis"
    ├── class-metaboxes.php       # Champs personnalisés admin
    ├── class-formulaire.php      # Formulaire frontend + AJAX
    ├── class-shortcodes.php      # Shortcodes WordPress
    └── class-widget.php          # Hook footer automatique
```

### Pattern de conception

- **Singleton Pattern** : Chaque classe utilise `get_instance()` pour éviter les duplications
- **Séparation des responsabilités** : Chaque classe a un rôle unique et défini
- **Hooks WordPress** : Utilisation intensive de `add_action()` et `add_filter()`

---

## ⚙️ Fonctionnalités

### 1. Custom Post Type "Témoignages" ✅

**Fichier:** `includes/class-cpt-avis.php`

- **Slug:** `avis`
- **Labels:** "💬 Témoignages" dans l'admin WordPress
- **Colonnes admin personnalisées:** Client, Note, Email, Date
- **URL publique:** `/temoignages/` (archive), `/temoignages/nom-temoignage/` (single)

**Métadonnées (postmeta) :**
- `_avis_email` : Email du client
- `_avis_rating` : Note sur 5 étoiles (1-5)
- `_avis_product_id` : Optionnel, pour lier à un produit
- `_avis_user_id` : Optionnel, ID utilisateur WordPress

**Code clé :**
```php
register_post_type('avis', array(
    'labels' => [...],
    'public' => true,
    'has_archive' => true,
    'supports' => array('title', 'editor'),
    'menu_icon' => 'dashicons-format-chat',
    'rewrite' => array('slug' => 'temoignages')
));
```

---

### 2. Hook WordPress (wp_footer) ✅

**Fichier:** `includes/class-widget.php`

**Action hook utilisé:** `wp_footer`

**Fonctionnalité :**
Affiche automatiquement un bandeau violet en bas de toutes les pages du site avec un témoignage aléatoire 4-5★.

**Code clé :**
```php
add_action('wp_footer', array($this, 'afficher_temoignage_footer'));
```

**Requête WP_Query :**
```php
$avis = get_posts(array(
    'post_type' => 'avis',
    'posts_per_page' => 1,
    'orderby' => 'rand',
    'meta_query' => array(
        array(
            'key' => '_avis_rating',
            'value' => 4,
            'compare' => '>=',
            'type' => 'NUMERIC'
        )
    )
));
```

**Résultat visuel :**
```
┌────────────────────────────────────────┐
│ 💬 Ce que disent nos clients          │
│ ⭐⭐⭐⭐⭐                              │
│ "Excellente boutique, je recommande!" │
│ — Marie                                │
└────────────────────────────────────────┘
```

---

### 3. Shortcodes WordPress ✅

**Fichier:** `includes/class-shortcodes.php`

#### a) `[derniers_avis]`

Affiche une liste des témoignages récents.

**Paramètres :**
- `limit` : Nombre de témoignages (défaut: 5)

**Utilisation :**
```
[derniers_avis limit="10"]
```

**Code PHP :**
```php
add_shortcode('derniers_avis', array($this, 'afficher_derniers_avis'));
```

#### b) `[note_moyenne]`

Affiche la note moyenne globale de satisfaction (toute la boutique).

**Utilisation :**
```
[note_moyenne]
```

**Affichage :**
```
┌──────────────────────────────┐
│ 4.8/5 ⭐⭐⭐⭐⭐              │
│ Basé sur 12 témoignages      │
└──────────────────────────────┘
```

#### c) `[formulaire_avis]`

Affiche le formulaire de soumission de témoignage.

**Utilisation :**
```
[formulaire_avis]
```

**Fonctionnalités :**
- Système d'étoiles cliquables (JavaScript)
- Soumission AJAX (sans rechargement)
- Validation client + serveur
- Sécurité : nonces WordPress

---

### 4. Metaboxes WordPress ✅

**Fichier:** `includes/class-metaboxes.php`

Interface admin pour saisir les métadonnées d'un témoignage :

- Email du client
- Note (1-5 étoiles)
- ID produit (optionnel)
- ID utilisateur (optionnel)

**Hook utilisé :**
```php
add_action('add_meta_boxes', array($this, 'add_metaboxes'));
add_action('save_post_avis', array($this, 'save_metabox_data'));
```

**Sécurité :**
- Nonce verification : `wp_verify_nonce()`
- Sanitization : `sanitize_email()`, `intval()`
- Capability check : `current_user_can('edit_post')`

---

### 5. Formulaire Frontend + AJAX ✅

**Fichier:** `includes/class-formulaire.php`

#### Affichage (Shortcode)
```php
add_shortcode('formulaire_avis', array($this, 'afficher_formulaire'));
```

#### Traitement AJAX
```php
add_action('wp_ajax_submit_avis', array($this, 'traiter_soumission_ajax'));
add_action('wp_ajax_nopriv_submit_avis', array($this, 'traiter_soumission_ajax'));
```

**Sécurité AJAX :**
```php
check_ajax_referer('cs_ajax_nonce', 'nonce');
$name = sanitize_text_field($_POST['client_name']);
$email = sanitize_email($_POST['client_email']);
$rating = intval($_POST['rating']);
$content = sanitize_textarea_field($_POST['avis_content']);
```

**Réponse JSON :**
```php
wp_send_json_success(array(
    'message' => 'Votre témoignage a été soumis avec succès !',
    'post_id' => $post_id
));
```

---

## 🎨 Assets Frontend

### CSS (`assets/css/avis-styles.css`)

**270 lignes** de styles organisés :

1. **Variables CSS** : Couleurs réutilisables
2. **Shortcode [derniers_avis]** : Cartes témoignages avec hover
3. **Shortcode [note_moyenne]** : Badge avec étoiles
4. **Formulaire** : Design moderne avec gradients
5. **Étoiles interactives** : Transitions et hover effects
6. **Footer widget** : Bandeau violet avec dégradé
7. **Responsive** : Mobile-friendly (@media queries)
8. **Animations** : fadeIn, translateX

**Chargement WordPress :**
```php
wp_enqueue_style('comment-shop-style', CS_PLUGIN_URL . 'assets/css/avis-styles.css', array(), CS_VERSION);
```

### JavaScript (`assets/js/avis-ajax.js`)

**125 lignes** de code jQuery :

1. **Système d'étoiles cliquables** :
   - Click → Coloration + radio check
   - Hover → Preview temporaire
   - Labels dynamiques ("Excellent", "Bien"...)

2. **Soumission AJAX** :
   - `preventDefault()` pour éviter rechargement
   - Validation côté client
   - `$.ajax()` vers `admin-ajax.php`
   - Messages succès/erreur dynamiques
   - Reset formulaire après succès

**Chargement WordPress :**
```php
wp_enqueue_script('comment-shop-ajax', CS_PLUGIN_URL . 'assets/js/avis-ajax.js', array('jquery'), CS_VERSION, true);

wp_localize_script('comment-shop-ajax', 'commentShopAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('cs_ajax_nonce')
));
```

---

## 🔒 Sécurité WordPress

### Nonces (CSRF Protection)

**Formulaire :**
```php
wp_nonce_field('submit_avis_nonce', 'avis_nonce');
```

**Vérification :**
```php
wp_verify_nonce($_POST['avis_nonce'], 'submit_avis_nonce');
```

**AJAX :**
```php
check_ajax_referer('cs_ajax_nonce', 'nonce');
```

### Sanitization (XSS Protection)

- `sanitize_text_field()` : Textes courts
- `sanitize_email()` : Emails
- `sanitize_textarea_field()` : Contenu long
- `intval()` : Nombres
- `esc_html()` : Affichage HTML
- `esc_attr()` : Attributs HTML
- `esc_url()` : URLs

### Validation

```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Email invalide';
}

if ($rating < 1 || $rating > 5) {
    return 'Note invalide';
}
```

---

## 📊 Base de données

### Tables utilisées

1. **wp_posts** : Stockage des témoignages (CPT)
   - `post_type = 'avis'`
   - `post_title` = Titre/nom client
   - `post_content` = Témoignage complet
   - `post_status` = 'publish' / 'draft'

2. **wp_postmeta** : Métadonnées
   - `_avis_email`
   - `_avis_rating`
   - `_avis_product_id`
   - `_avis_user_id`

### Requêtes SQL (via WP_Query)

**Témoignages récents :**
```php
get_posts(array(
    'post_type' => 'avis',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
));
```

**Note moyenne globale :**
```php
$avis = get_posts(array('post_type' => 'avis', 'posts_per_page' => -1));
foreach ($avis as $item) {
    $total += get_post_meta($item->ID, '_avis_rating', true);
}
$moyenne = $total / count($avis);
```

---

## 🚀 Installation

### Prérequis

- WordPress 5.8+
- PHP 7.4+
- WooCommerce (optionnel mais recommandé)

### Étapes

1. **Télécharger l'extension**
   ```bash
   cd wp-content/plugins/
   git clone [repo-url] comment-shop
   ```

2. **Activer dans WordPress**
   - Admin → Extensions → Activer "Comment Shop"

3. **Flush rewrite rules**
   - Admin → Réglages → Permaliens → Enregistrer

4. **Créer des témoignages**
   - Admin → 💬 Témoignages → Ajouter

5. **Utiliser les shortcodes**
   - Créer page "Témoignages"
   - Ajouter `[derniers_avis limit="10"]`

---

## 📝 Utilisation

### Scénario 1 : Page témoignages

**Créer :** Admin → Pages → Ajouter

**Contenu :**
```
[note_moyenne]

[derniers_avis limit="15"]
```

**Résultat :** Liste complète des témoignages avec note globale

---

### Scénario 2 : Formulaire de soumission

**Créer :** Admin → Pages → Ajouter (Titre: "Laisser un témoignage")

**Contenu :**
```
Vous êtes satisfait de votre achat ? Partagez votre expérience !

[formulaire_avis]
```

**Résultat :** Formulaire interactif avec étoiles cliquables

---

### Scénario 3 : Widget footer automatique

**Rien à faire !** Le hook `wp_footer` affiche automatiquement un témoignage aléatoire en bas de chaque page.

---

## 🔄 Différence avec WooCommerce

| **Avis WooCommerce** | **Témoignages Comment Shop** |
|---------------------|------------------------------|
| Type : Commentaires WordPress | Type : Custom Post Type |
| Table : `wp_comments` | Table : `wp_posts` |
| Lié à UN produit (obligatoire) | Témoignages généraux (optionnel produit) |
| Affiché sur page produit uniquement | Affichable PARTOUT (shortcodes) |
| Pas de hook automatique | Hook footer automatique |
| Interface admin standard | Interface admin dédiée |
| Objectif : Évaluer CE produit | Objectif : Crédibilité GLOBALE |

**Analogie :**
- **WooCommerce** = Amazon (avis produits spécifiques)
- **Comment Shop** = Trustpilot (satisfaction globale boutique)

---

## 🎓 Concepts WordPress démontrés

### 1. Custom Post Types
```php
register_post_type('avis', [...]);
```

### 2. Hooks (Actions & Filters)
```php
add_action('wp_footer', [...]);
add_filter('manage_avis_posts_columns', [...]);
```

### 3. Shortcodes
```php
add_shortcode('derniers_avis', [...]);
```

### 4. AJAX WordPress
```php
add_action('wp_ajax_submit_avis', [...]);
add_action('wp_ajax_nopriv_submit_avis', [...]);
```

### 5. Metaboxes
```php
add_meta_box('avis_details', [...]);
```

### 6. Enqueue Scripts/Styles
```php
wp_enqueue_style('comment-shop-style', [...]);
wp_enqueue_script('comment-shop-ajax', [...]);
wp_localize_script('comment-shop-ajax', [...]);
```

### 7. Sécurité
- Nonces (`wp_nonce_field`, `wp_verify_nonce`)
- Sanitization (`sanitize_*`)
- Validation (`filter_var`)
- Capability checks (`current_user_can`)

### 8. Singleton Pattern
```php
private static $instance = null;
public static function get_instance() { [...] }
```

---

## 📈 Statistiques

- **Fichiers PHP :** 6
- **Lignes de code PHP :** ~850
- **Lignes CSS :** ~270
- **Lignes JavaScript :** ~125
- **Total :** ~1245 lignes

**Répartition :**
- CPT : 128 lignes
- Metaboxes : 151 lignes
- Formulaire : 234 lignes
- Shortcodes : 125 lignes (après simplification)
- Widget : 115 lignes
- Assets : 395 lignes

---

## 🐛 Débogage

### Activer le mode debug WordPress

**wp-config.php :**
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

**Logs :** `wp-content/debug.log`

### Vérifier les hooks

**Plugin recommandé :** Query Monitor

### Tester AJAX

**Console navigateur :**
```javascript
console.log(commentShopAjax.ajaxurl);
console.log(commentShopAjax.nonce);
```

---

## 📚 Documentation WordPress utilisée

- [Plugin Handbook](https://developer.wordpress.org/plugins/)
- [Custom Post Types](https://developer.wordpress.org/plugins/post-types/)
- [Shortcode API](https://codex.wordpress.org/Shortcode_API)
- [AJAX in Plugins](https://codex.wordpress.org/AJAX_in_Plugins)
- [Metadata API](https://developer.wordpress.org/plugins/metadata/)

---

## 🤝 Contribution

**Auteur principal :** Johan PRISO  
**Projet académique** : WordPress + WooCommerce  
**Date :** Décembre 2024

---

## 📄 Licence

Ce projet est développé dans un cadre éducatif.

---

## ✅ Checklist Projet Académique

- [x] **1 Custom Post Type** : "avis" (témoignages)
- [x] **1 Hook minimum** : `wp_footer` (widget automatique)
- [x] **Architecture propre** : Classes séparées, Singleton pattern
- [x] **Sécurité** : Nonces, sanitization, validation
- [x] **AJAX** : Soumission formulaire sans rechargement (bonus)
- [x] **Assets** : CSS + JavaScript optimisés
- [x] **Documentation** : README complet
- [x] **Fonctionnel** : Testé et opérationnel

---

**🎉 Extension prête pour évaluation académique !**
