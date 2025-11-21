# ⚡ Installation Rapide - 5 Minutes Chrono

## 📋 Prérequis
- WordPress 5.0+
- Un thème WordPress actif
- Accès FTP ou au tableau de bord WordPress

## 🚀 Installation en 3 Étapes

### ÉTAPE 1 : Télécharger le Fichier

Téléchargez le fichier `category-search-modal.php` depuis ce repository.

### ÉTAPE 2 : Uploader le Fichier

**Option A : Via FTP**
1. Connectez-vous à votre FTP
2. Allez dans `/wp-content/themes/votre-theme/`
3. Uploadez le fichier `category-search-modal.php`

**Option B : Via l'Éditeur de Thème WordPress**
1. Allez dans **Apparence > Éditeur de thème**
2. Créez un nouveau fichier appelé `category-search-modal.php`
3. Copiez-collez tout le contenu du fichier

### ÉTAPE 3 : Activer le Code

Ouvrez votre fichier `functions.php` et ajoutez cette ligne **à la fin** du fichier :

```php
require_once get_template_directory() . '/category-search-modal.php';
```

**C'est tout ! ✅**

## 🧪 Test

1. Allez sur votre page de recherche
2. Cliquez sur le sélecteur de catégories "Que recherchez-vous ?"
3. La modal devrait s'ouvrir avec vos catégories

## ❌ Ça ne fonctionne pas ?

### La modal ne s'ouvre pas

**Cause probable :** jQuery n'est pas chargé ou conflit JavaScript

**Solution :**
1. Ouvrez la console du navigateur (touche F12)
2. Cherchez les erreurs en rouge
3. Vérifiez que jQuery est chargé :
   - Dans la console, tapez : `jQuery`
   - Si vous voyez "jQuery is not defined", ajoutez ceci dans `functions.php` :
   ```php
   wp_enqueue_script('jquery');
   ```

### Les catégories ne s'affichent pas

**Cause probable :** Le nom de votre taxonomie est différent

**Solution :**
1. Ouvrez `category-search-modal.php`
2. Cherchez la ligne : `'taxonomy' => 'listing_category',`
3. Remplacez `listing_category` par le nom de votre taxonomie
   - Pour trouver le nom : allez dans votre base de données, table `wp_term_taxonomy`, colonne `taxonomy`
   - Ou demandez au développeur de votre thème

### Le select original est toujours visible

**Cause probable :** La classe CSS est différente

**Solution :**
1. Faites clic droit > Inspecter sur votre sélecteur de catégories
2. Notez la classe principale (ex: `select-taxonomy`)
3. Dans le CSS, cherchez `.select-taxonomy` et remplacez par votre classe

### Le style ne ressemble pas à l'image

**Cause probable :** Conflit CSS avec votre thème

**Solution :**
1. Ajoutez `!important` aux styles qui ne fonctionnent pas
2. Exemple :
   ```css
   .bringueuses-category-modal {
       background: white !important;
   }
   ```

## 🎨 Personnalisation Rapide

### Changer la couleur principale

Dans `category-search-modal.php`, cherchez toutes les occurrences de `#007bff` et remplacez par votre couleur.

**Exemple : Passer au vert**
- Utilisez la fonction Rechercher/Remplacer de votre éditeur
- Remplacez : `#007bff`
- Par : `#28a745`

### Changer les textes

Cherchez et modifiez :
- `Que recherchez-vous ?` → Votre texte
- `Catégories` → Votre texte
- `Effacer` → Votre texte
- `Afficher les résultats` → Votre texte

### Modifier la taille de la modal

Dans le CSS, cherchez `.bringueuses-category-modal` et modifiez :
```css
max-width: 800px; /* Changez cette valeur */
```

## 📞 Support Rapide

### Problèmes Courants

| Problème | Solution Rapide |
|----------|----------------|
| Modal ne s'ouvre pas | Vérifiez la console (F12) pour les erreurs JS |
| Pas de catégories | Vérifiez le nom de la taxonomie |
| Style cassé | Ajoutez `!important` aux CSS |
| Bouton invisible | Vérifiez la classe `.select-taxonomy` |
| Erreur PHP | Vérifiez les guillemets et points-virgules |

### Commandes de Débogage

Ajoutez temporairement dans `functions.php` pour voir les erreurs :

```php
// Activer le mode debug
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
```

**⚠️ N'oubliez pas de désactiver en production !**

### Vérifier les Catégories

Ajoutez ce code temporaire dans `functions.php` pour lister vos catégories :

```php
add_action('wp_footer', function() {
    $cats = get_terms(array('taxonomy' => 'listing_category', 'hide_empty' => false));
    echo '<pre>';
    print_r($cats);
    echo '</pre>';
});
```

Rechargez votre page, descendez en bas, et vous verrez la liste de vos catégories.

## 🔄 Mise à Jour

Pour mettre à jour le code :
1. Sauvegardez d'abord l'ancien fichier
2. Remplacez `category-search-modal.php` par la nouvelle version
3. Si vous aviez des personnalisations, réappliquez-les

## 🗑️ Désinstallation

Pour retirer la fonctionnalité :
1. Ouvrez `functions.php`
2. Supprimez ou commentez la ligne :
   ```php
   // require_once get_template_directory() . '/category-search-modal.php';
   ```
3. (Optionnel) Supprimez le fichier `category-search-modal.php`

## ✅ Checklist Post-Installation

- [ ] La modal s'ouvre au clic
- [ ] Les catégories s'affichent
- [ ] Les sous-catégories se déplient
- [ ] Les checkboxes fonctionnent
- [ ] Le bouton "Effacer" fonctionne
- [ ] Le bouton "Afficher les résultats" ferme la modal
- [ ] La recherche fonctionne avec les catégories sélectionnées
- [ ] Le responsive fonctionne sur mobile
- [ ] Pas d'erreurs dans la console (F12)

## 📚 Documentation Complète

Pour plus de détails, consultez :
- `README.md` - Documentation complète
- `PERSONNALISATION.md` - Guide de personnalisation avancée
- `category-modal.css` - Version CSS séparée (optionnel)

## 💬 Besoin d'Aide ?

Si vous êtes bloqué après avoir suivi ce guide :
1. Vérifiez la console du navigateur (F12)
2. Vérifiez les logs WordPress (`/wp-content/debug.log`)
3. Testez avec le thème WordPress par défaut (Twenty Twenty-Four)
4. Désactivez temporairement les autres plugins

---

**Temps d'installation estimé : 5 minutes** ⏱️

**Difficulté : Facile** ⭐⭐☆☆☆

Bonne installation ! 🎉
