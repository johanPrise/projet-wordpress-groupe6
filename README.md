# Projet WordPress - TP

Ce projet WordPress est développé avec **Local by Flywheel**.

## 🚀 Installation pour les développeurs

### Prérequis
- [Local by Flywheel](https://localwp.com/) installé sur votre machine

### Méthode 1 : Utilisation du Blueprint Local (Recommandé)
1. Télécharger le fichier Blueprint `.zip` (fourni séparément)
2. Ouvrir Local
3. Cliquer sur le **+** → **Import**
4. Sélectionner le fichier Blueprint
5. Local configure automatiquement tout

### Méthode 2 : Installation manuelle depuis GitHub
1. Créer un nouveau site dans Local :
   - Nom du site : `tp-wordpress` (ou autre)
   - PHP : 8.0+ (selon config)
   - Serveur web : nginx ou Apache
   - Base de données : MySQL

2. Cloner ce dépôt :
   ```bash
   cd "/chemin/vers/local/sites/votre-site/app/public"
   git clone [URL_DU_DEPOT] .
   ```

3. Configurer WordPress :
   - Copier `wp-config-sample.php` vers `wp-config.php`
   - Modifier les paramètres de connexion BDD (utiliser ceux fournis par Local)

4. Importer la base de données :
   - Télécharger le dump SQL (fourni séparément)
   - Dans Local → Database → Ouvrir Adminer/phpMyAdmin
   - Importer le fichier `.sql`

5. Mettre à jour les URLs (si nécessaire) :
   ```sql
   UPDATE wp_options SET option_value = 'http://votre-site.local' WHERE option_name = 'siteurl';
   UPDATE wp_options SET option_value = 'http://votre-site.local' WHERE option_name = 'home';
   ```

## 📁 Structure du projet

- `wp-content/themes/` - Thèmes personnalisés
- `wp-content/plugins/` - Plugins personnalisés
- `wp-content/mu-plugins/` - Must-use plugins

## ⚙️ Configuration

Les identifiants WordPress par défaut (à changer) :
- **URL** : Définie par Local
- **Admin** : Voir documentation séparée

## 📝 Notes

- Le fichier `wp-config.php` est exclu du dépôt pour des raisons de sécurité
- Les uploads ne sont pas versionnés (à synchroniser séparément si nécessaire)
- Utiliser Local pour gérer l'environnement de développement
