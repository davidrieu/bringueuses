# 🐛 Guide de Débogage - Modal Catégories

Ce guide vous aide à identifier pourquoi la modal ne s'ouvre pas.

## 📋 Étape 1 : Ouvrir la Console du Navigateur

1. **Sur Chrome/Edge** : Appuyez sur `F12` ou `Ctrl+Shift+I`
2. **Sur Firefox** : Appuyez sur `F12` ou `Ctrl+Shift+K`
3. **Sur Safari** : `Cmd+Option+C` (activez d'abord le menu Développement)

Cliquez sur l'onglet **"Console"**

## 🔍 Étape 2 : Analyser les Messages

Rechargez votre page et cherchez ces messages dans la console :

### ✅ Messages Normaux (tout fonctionne)

```
=== BRINGUEUSES DEBUG ===
jQuery chargé: true
Nombre de .select-taxonomy trouvés: 1
Condition validée - Injection de la modal
Modal injectée dans le body
Modal trouvée: 1
Overlay trouvé: 1
Bouton existant trouvé: 1
Select original trouvé: 1
Handlers Bootstrap Select supprimés
Surveillance du dropdown activée
Gestionnaire de clic attaché sur: 2 éléments
=== INITIALISATION TERMINEE ===
```

**Ensuite, cliquez sur le bouton "Que recherchez-vous ?"** et vous devriez voir :

```
*** CLIC DETECTE SUR LE BOUTON ***
Dropdown fermé
Modal ouverte - Classes active ajoutées
Modal visible: true
Overlay visible: true
```

### ❌ Problème 1 : jQuery non chargé

**Message :**
```
Uncaught ReferenceError: jQuery is not defined
```

**Solution :**
Ajoutez dans votre `functions.php` AVANT la ligne `require_once` :
```php
function bringueuses_enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'bringueuses_enqueue_jquery', 1);
```

### ❌ Problème 2 : .select-taxonomy non trouvé

**Message :**
```
Nombre de .select-taxonomy trouvés: 0
ERREUR: Conditions non remplies
.select-taxonomy existe: false
```

**Solution :**
La classe CSS est différente dans votre thème. Pour trouver la bonne classe :

1. Sur votre page, faites **clic droit** sur le bouton de recherche
2. Choisissez **"Inspecter"**
3. Notez la classe principale du conteneur (ex: `main-search-input-item`)
4. Dans `category-search-modal.php`, remplacez toutes les occurrences de `.select-taxonomy` par votre classe

**Exemple avec VSCode ou éditeur de texte :**
- Rechercher : `.select-taxonomy`
- Remplacer par : `.main-search-input-item` (ou votre classe)

### ❌ Problème 3 : Clic non détecté

**Message après avoir cliqué :**
```
Rien ne s'affiche dans la console
```

**Solutions à tester dans l'ordre :**

#### A) Test manuel dans la console
Tapez ceci dans la console :
```javascript
jQuery('.select-taxonomy .btn.dropdown-toggle').length
```

Si le résultat est `0`, le sélecteur est incorrect.

#### B) Trouver le bon sélecteur
Dans la console, tapez :
```javascript
// Trouver le bouton
jQuery('button[data-toggle="dropdown"]').each(function(i, el) {
    console.log('Bouton trouvé:', el, 'Classes:', el.className);
});
```

Notez les classes affichées et modifiez le code en conséquence.

#### C) Forcer l'attachement du clic
Dans la console, testez manuellement :
```javascript
jQuery('.select-taxonomy .btn.dropdown-toggle').on('click', function(e) {
    e.preventDefault();
    console.log('CLIC FORCE');
    alert('Clic détecté !');
    return false;
});
```

Puis cliquez sur le bouton. Si l'alert s'affiche, le problème vient du timing (le script se charge trop tôt).

**Solution pour le timing :**
Remplacez dans le code :
```javascript
jQuery(document).ready(function($) {
```

Par :
```javascript
jQuery(window).on('load', function($) {
    $ = jQuery;
```

### ❌ Problème 4 : Modal invisible

**Message :**
```
*** CLIC DETECTE SUR LE BOUTON ***
Modal ouverte - Classes active ajoutées
Modal visible: false
Overlay visible: false
```

**Solution 1 - Conflit CSS :**
Le CSS de votre thème cache la modal. Ajoutez dans le code CSS :
```css
.bringueuses-modal-overlay.active,
.bringueuses-category-modal.active {
    z-index: 999999 !important;
}
```

**Solution 2 - Vérifier le z-index :**
Dans la console :
```javascript
console.log('Z-index modal:', jQuery('#bringueuses-category-modal').css('z-index'));
console.log('Z-index overlay:', jQuery('#bringueuses-modal-overlay').css('z-index'));
```

Si les valeurs sont trop faibles ou "auto", augmentez-les dans le CSS.

### ❌ Problème 5 : Erreur JavaScript

**Message :**
```
Uncaught TypeError: Cannot read property 'xxx' of undefined
```

**Solution :**
Copiez l'erreur complète et vérifiez :
1. Que vous avez copié TOUT le code du fichier
2. Qu'il n'y a pas de caractères spéciaux cassés
3. Que toutes les apostrophes sont correctes

## 🧪 Tests Manuels

### Test 1 : Vérifier que la modal existe dans le DOM

Dans la console :
```javascript
console.log('Modal dans le DOM:', jQuery('#bringueuses-category-modal').length);
console.log('Overlay dans le DOM:', jQuery('#bringueuses-modal-overlay').length);
```

Résultat attendu : `1` pour chaque

### Test 2 : Forcer l'ouverture de la modal

Dans la console :
```javascript
jQuery('#bringueuses-modal-overlay').addClass('active');
jQuery('#bringueuses-category-modal').addClass('active');
```

Si la modal s'affiche, le problème vient du clic qui n'est pas intercepté.

### Test 3 : Vérifier les catégories

Dans la console :
```javascript
jQuery('.bringueuses-category-item').each(function() {
    console.log('Catégorie:', jQuery(this).find('.bringueuses-category-name span').first().text());
});
```

Vous devriez voir vos catégories WordPress listées.

## 📸 Envoyer un Rapport de Bug

Si rien ne fonctionne, copiez ces informations :

1. **Console complète** (copier tout le texte de la console)
2. **Structure HTML du bouton** (clic droit > Inspecter > copier l'élément HTML)
3. **Version de WordPress** (Tableau de bord > Mises à jour)
4. **Thème utilisé**
5. **Plugins actifs** (surtout ceux liés aux formulaires)

## 🔧 Solution Temporaire : Version Sans Bootstrap Select

Si vraiment rien ne fonctionne, utilisez cette version simplifiée qui crée un nouveau bouton :

Dans `category-search-modal.php`, remplacez la section "Intercepter le clic" par :
```javascript
// Cacher le Bootstrap Select
$('.select-taxonomy .bootstrap-select').hide();

// Créer un bouton simple
var newBtn = $('<button type="button" class="btn btn-default bringueuses-trigger">Que recherchez-vous ?</button>');
$('.select-taxonomy').prepend(newBtn);

// Clic sur le nouveau bouton
newBtn.on('click', function(e) {
    e.preventDefault();
    $overlay.addClass('active');
    $modal.addClass('active');
    $('body').css('overflow', 'hidden');
    return false;
});
```

## 💡 Astuces

### Désactiver temporairement le code
Pour tester si le problème vient du code :
```php
// Dans functions.php, commentez la ligne :
// require_once get_template_directory() . '/category-search-modal.php';
```

### Tester sur un autre navigateur
Parfois le problème vient d'une extension de navigateur qui bloque le JavaScript.

### Mode navigation privée
Testez en mode incognito/privé pour éviter les problèmes de cache.

### Vider tous les caches
1. Cache du navigateur (Ctrl+Shift+Suppr)
2. Cache WordPress (si vous avez un plugin de cache)
3. Cache CDN (si applicable)

## 📞 Besoin d'Aide ?

Si après tous ces tests ça ne fonctionne toujours pas :
1. Copiez TOUTE la sortie de la console
2. Faites une capture d'écran de l'inspecteur sur le bouton
3. Indiquez votre thème et version de WordPress

Bonne chance ! 🍀
