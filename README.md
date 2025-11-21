# Système de Recherche par Catégories avec Modal (Style Funbooker)

Ce code transforme votre sélecteur de catégories WordPress en une modal élégante similaire à Funbooker.

## 🚀 Installation

### Méthode 1 : Copier tout le contenu dans functions.php

1. Ouvrez le fichier `category-search-modal.php`
2. Copiez TOUT le contenu (y compris la balise `<?php` du début)
3. Collez-le à la fin de votre fichier `functions.php` de votre thème WordPress (généralement dans `/wp-content/themes/votre-theme/functions.php`)
4. Sauvegardez le fichier

### Méthode 2 : Inclure le fichier (recommandé)

1. Téléchargez le fichier `category-search-modal.php` dans votre thème WordPress
2. Dans votre `functions.php`, ajoutez cette ligne :

```php
require_once get_template_directory() . '/category-search-modal.php';
```

## ✨ Fonctionnalités

- ✅ **Modal élégante** qui s'ouvre au clic sur le sélecteur
- ✅ **Catégories dépliables** : cliquez sur une catégorie principale pour voir les sous-catégories
- ✅ **Compteurs** : affiche le nombre d'articles par catégorie
- ✅ **Sélection multiple** : cochez/décochez les catégories
- ✅ **Sélection en cascade** : cocher une catégorie principale coche toutes ses sous-catégories
- ✅ **Bouton "Effacer"** : réinitialise toutes les sélections
- ✅ **Responsive** : fonctionne parfaitement sur mobile
- ✅ **Compatible** : fonctionne avec votre formulaire de recherche existant

## 🎨 Personnalisation

### Modifier les couleurs

Pour changer la couleur principale (actuellement bleu), modifiez ces valeurs dans le CSS :

```css
/* Cherchez ces lignes dans la fonction bringueuses_get_modal_css() */
accent-color: #007bff;  /* Couleur des checkboxes */
background-color: #007bff;  /* Couleur du bouton */
color: #007bff;  /* Couleur du bouton Effacer */
```

Remplacez `#007bff` par votre couleur de marque.

### Modifier le texte

Pour changer les textes, modifiez ces lignes :

```javascript
// Dans la fonction bringueuses_get_modal_js()
'<span class="placeholder">Que recherchez-vous ?</span>'  // Texte du bouton
```

```php
// Dans la fonction bringueuses_get_modal_html()
'<h3>Catégories</h3>'  // Titre de la modal
'<button class="bringueuses-clear-btn">Effacer</button>'  // Bouton effacer
'<button class="bringueuses-submit-btn">Afficher les résultats</button>'  // Bouton soumettre
```

## 🔧 Configuration avancée

### Changer la taxonomie

Si votre taxonomie ne s'appelle pas `listing_category`, modifiez cette ligne dans `bringueuses_get_modal_html()` :

```php
'taxonomy' => 'listing_category',  // Remplacez par le nom de votre taxonomie
```

### Afficher uniquement les catégories avec des articles

Changez `'hide_empty' => false,` en `'hide_empty' => true,` dans les deux `get_terms()`.

## 📱 Compatibilité

- ✅ WordPress 5.0+
- ✅ jQuery (inclus avec WordPress)
- ✅ Fonctionne avec Bootstrap Select (le masque automatiquement)
- ✅ Tous les navigateurs modernes
- ✅ Mobile responsive

## 🐛 Résolution des problèmes

### La modal ne s'ouvre pas

1. Vérifiez que jQuery est chargé sur votre site
2. Ouvrez la console du navigateur (F12) et vérifiez les erreurs JavaScript
3. Assurez-vous que la classe `.select-taxonomy` existe bien dans votre HTML

### Les catégories ne s'affichent pas

1. Vérifiez le nom de votre taxonomie dans le code
2. Assurez-vous d'avoir des catégories créées dans WordPress
3. Vérifiez que les catégories ont bien des relations parent/enfant si vous voulez la hiérarchie

### Le style ne s'applique pas

1. Vérifiez que le CSS est bien chargé (inspectez l'élément dans votre navigateur)
2. Il peut y avoir des conflits avec votre thème - utilisez `!important` si nécessaire
3. Videz le cache de votre navigateur et de WordPress

## 📄 Structure du code

Le code est organisé en 4 fonctions principales :

1. **`bringueuses_category_search_modal_assets()`** : Charge les scripts et styles
2. **`bringueuses_get_modal_css()`** : Retourne le CSS de la modal
3. **`bringueuses_get_modal_js()`** : Retourne le JavaScript pour les interactions
4. **`bringueuses_get_modal_html()`** : Génère le HTML de la modal avec les catégories

## 🎯 Comment ça fonctionne

1. Le code détecte votre sélecteur Bootstrap Select existant
2. Il intercepte le clic sur le bouton Bootstrap Select
3. Au lieu d'ouvrir le dropdown, une modal personnalisée s'ouvre
4. La modal affiche vos catégories WordPress de manière hiérarchique
5. Vous sélectionnez les catégories souhaitées avec des checkboxes
6. Au clic sur "Afficher les résultats", le select original est mis à jour
7. Le texte du bouton Bootstrap Select affiche le nombre de catégories sélectionnées
8. Le formulaire de recherche fonctionne normalement avec les valeurs sélectionnées

## 🆘 Support

Si vous rencontrez des problèmes :

1. Vérifiez que vous avez copié TOUT le code
2. Assurez-vous qu'il n'y a pas d'erreurs PHP (activez `WP_DEBUG` temporairement)
3. Vérifiez la console JavaScript de votre navigateur (F12)

## 📝 Licence

Ce code est libre d'utilisation pour votre projet WordPress.
