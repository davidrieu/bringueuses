# 🎨 Guide de Personnalisation de la Modal

Ce guide vous explique comment personnaliser l'apparence et le comportement de votre modal de recherche par catégories.

## 🌈 Changer les Couleurs

### Couleur Principale (Bleu par défaut)

Pour changer la couleur principale du bleu (#007bff) vers votre couleur de marque, remplacez toutes les occurrences de `#007bff` dans le CSS :

**Éléments affectés :**
- Couleur des checkboxes cochées
- Couleur du bouton "Afficher les résultats"
- Couleur du bouton "Effacer"
- Couleur du texte du nombre de catégories sélectionnées

**Exemple pour du VERT :**
```css
/* Remplacez #007bff par #28a745 */
accent-color: #28a745;
background-color: #28a745;
color: #28a745;
```

**Exemple pour du ROUGE :**
```css
/* Remplacez #007bff par #dc3545 */
accent-color: #dc3545;
background-color: #dc3545;
color: #dc3545;
```

### Couleur de l'Overlay (fond sombre)

```css
/* Dans .bringueuses-modal-overlay */
background-color: rgba(0, 0, 0, 0.5); /* 0.5 = 50% d'opacité */

/* Pour un fond plus sombre */
background-color: rgba(0, 0, 0, 0.7);

/* Pour un fond coloré */
background-color: rgba(0, 50, 100, 0.5); /* Bleu foncé */
```

### Couleur de Fond de la Modal

```css
/* Dans .bringueuses-category-modal */
background: white; /* Changez en n'importe quelle couleur */

/* Exemple : fond gris clair */
background: #f5f5f5;
```

## 📐 Modifier les Tailles

### Taille de la Modal

```css
/* Dans .bringueuses-category-modal */
max-width: 800px; /* Largeur maximale */
width: 90%; /* Largeur en pourcentage */
max-height: 90vh; /* Hauteur maximale */

/* Pour une modal plus grande */
max-width: 1000px;
width: 95%;

/* Pour une modal plus petite */
max-width: 600px;
width: 85%;
```

### Taille des Checkboxes

```css
/* Dans les input[type="checkbox"] */
width: 18px;
height: 18px;

/* Pour des checkboxes plus grandes */
width: 22px;
height: 22px;

/* Pour des checkboxes plus petites */
width: 15px;
height: 15px;
```

### Taille de la Police

```css
/* Titre de la modal */
.bringueuses-modal-header h3 {
    font-size: 20px; /* Augmentez ou diminuez */
}

/* Noms des catégories */
.bringueuses-category-name {
    font-size: 16px; /* Ajoutez cette ligne */
}

/* Compteurs */
.bringueuses-category-count {
    font-size: 13px;
}

/* Boutons */
.bringueuses-submit-btn {
    font-size: 15px;
}
```

## 🎭 Modifier les Animations

### Animation d'Ouverture de la Modal

```css
/* Dans .bringueuses-category-modal */
transition: all 0.3s ease; /* Durée de 0.3 secondes */

/* Pour une animation plus rapide */
transition: all 0.15s ease;

/* Pour une animation plus lente */
transition: all 0.5s ease;

/* Pour un effet différent */
transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55); /* Effet "bounce" */
```

### Animation de Dépliage des Sous-catégories

```css
/* Modifier dans le JavaScript, dans la fonction bringueuses_get_modal_js() */
/* Cherchez : */
\$subcats.toggleClass('expanded');

/* Vous pouvez ajouter une transition CSS */
.bringueuses-subcategories {
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
}

.bringueuses-subcategories.expanded {
    max-height: 1000px; /* Suffisamment grand pour contenir toutes les sous-catégories */
}
```

## 🔲 Modifier le Style des Compteurs

### Style des Badges de Comptage

```css
.bringueuses-category-count {
    background-color: #f0f0f0;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 500;
}

/* Style arrondi */
.bringueuses-category-count {
    border-radius: 12px;
    padding: 3px 10px;
}

/* Style coloré */
.bringueuses-category-count {
    background-color: #007bff;
    color: white;
}

/* Style avec bordure */
.bringueuses-category-count {
    background-color: transparent;
    border: 1px solid #007bff;
    color: #007bff;
}
```

## 🔘 Modifier les Boutons

### Style du Bouton "Afficher les résultats"

```css
/* Bouton arrondi */
.bringueuses-submit-btn {
    border-radius: 25px; /* Au lieu de 5px */
}

/* Bouton avec ombre */
.bringueuses-submit-btn {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Bouton en majuscules */
.bringueuses-submit-btn {
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Bouton plus large */
.bringueuses-submit-btn {
    padding: 12px 32px; /* Au lieu de 10px 24px */
    min-width: 200px;
}
```

### Style du Bouton "Effacer"

```css
/* Bouton avec fond */
.bringueuses-clear-btn {
    background-color: #f8f9fa;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
}

/* Bouton avec bordure */
.bringueuses-clear-btn {
    border: 1px solid #007bff;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
}
```

## 📱 Personnalisation Mobile

### Modifier le Comportement Mobile

```css
@media (max-width: 768px) {
    /* Modal en plein écran sur mobile */
    .bringueuses-category-modal {
        width: 100%;
        max-height: 100vh;
        border-radius: 0;
    }

    /* Ou garder un peu de marge */
    .bringueuses-category-modal {
        width: 95%;
        max-height: 95vh;
        border-radius: 12px;
    }
}
```

## 🎯 Fonctionnalités Avancées

### Ajouter une Icône au Bouton Principal

Dans la fonction `bringueuses_get_modal_js()`, modifiez :

```javascript
var customBtn = '<button type="button" class="bringueuses-custom-select-btn" id="bringueuses-open-modal">' +
    '<span class="placeholder">🔍 Que recherchez-vous ?</span>' + // Ajoutez un emoji
    '<span class="caret"></span>' +
    '</button>';
```

Ou avec Font Awesome :

```javascript
var customBtn = '<button type="button" class="bringueuses-custom-select-btn" id="bringueuses-open-modal">' +
    '<span class="placeholder"><i class="fa fa-search"></i> Que recherchez-vous ?</span>' +
    '<span class="caret"></span>' +
    '</button>';
```

### Ajouter un Champ de Recherche dans la Modal

Dans la fonction `bringueuses_get_modal_html()`, après le header, ajoutez :

```php
$html .= '<div class="bringueuses-search-box" style="padding: 15px 25px; border-bottom: 1px solid #e5e5e5;">';
$html .= '<input type="text" id="bringueuses-category-search" placeholder="Rechercher une catégorie..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">';
$html .= '</div>';
```

Puis ajoutez le JavaScript pour filtrer :

```javascript
$('#bringueuses-category-search').on('keyup', function() {
    var searchTerm = $(this).val().toLowerCase();
    $('.bringueuses-category-item').each(function() {
        var categoryText = $(this).text().toLowerCase();
        if (categoryText.indexOf(searchTerm) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});
```

### Afficher les Catégories Dépliées par Défaut

Dans la fonction `bringueuses_get_modal_html()`, ajoutez la classe `expanded` :

```php
// Pour les sous-catégories
$html .= '<div class="bringueuses-subcategories expanded">'; // Ajoutez "expanded"

// Et pour le bouton toggle
$html .= '<button class="bringueuses-category-toggle expanded" ...>'; // Ajoutez "expanded"
```

### Limiter le Nombre de Sélections

Dans le JavaScript, après la gestion des checkboxes :

```javascript
$('.bringueuses-category-modal input[type="checkbox"]').on('change', function() {
    var checkedCount = $('.bringueuses-category-modal input[type="checkbox"]:checked').length;
    var maxSelections = 5; // Limite à 5 sélections

    if (checkedCount > maxSelections) {
        alert('Vous ne pouvez sélectionner que ' + maxSelections + ' catégories maximum.');
        $(this).prop('checked', false);
        return false;
    }
});
```

## 🌐 Traductions

Pour changer la langue, modifiez ces textes :

```php
// Dans bringueuses_get_modal_html()
'<h3>Catégories</h3>' // Titre
'<button class="bringueuses-clear-btn">Effacer</button>' // Bouton effacer
'<button class="bringueuses-submit-btn">Afficher les résultats</button>' // Bouton soumettre
```

```javascript
// Dans bringueuses_get_modal_js()
'<span class="placeholder">Que recherchez-vous ?</span>' // Placeholder
.text(selectedValues.length + ' catégorie(s) sélectionnée(s)') // Texte de sélection
```

**Exemple en anglais :**
- "Categories"
- "Clear"
- "Show results"
- "What are you looking for?"
- selectedValues.length + ' category(ies) selected'

## 💡 Conseils de Design

### Pour un Look Moderne
- Utilisez des coins arrondis (border-radius: 8px)
- Ajoutez des ombres douces (box-shadow)
- Utilisez des transitions fluides (transition: 0.3s)
- Espacez bien les éléments (padding généreux)

### Pour un Look Minimaliste
- Supprimez les ombres
- Utilisez des coins carrés (border-radius: 0)
- Limitez les couleurs (noir, blanc, gris)
- Réduisez les paddings

### Pour un Look Playful
- Ajoutez des emojis 🎉
- Utilisez des couleurs vives
- Ajoutez des animations amusantes
- Utilisez des polices arrondies

## 🔧 Exemples Complets

### Exemple 1 : Thème Sombre

```css
/* Modal fond sombre */
.bringueuses-category-modal {
    background: #2d3748;
    color: white;
}

.bringueuses-modal-header {
    border-bottom-color: #4a5568;
}

.bringueuses-category-name,
.bringueuses-subcategory-name {
    color: white;
}

.bringueuses-category-count {
    background-color: #4a5568;
    color: #cbd5e0;
}

.bringueuses-modal-footer {
    background-color: #1a202c;
    border-top-color: #4a5568;
}

.bringueuses-submit-btn {
    background-color: #48bb78;
}

.bringueuses-submit-btn:hover {
    background-color: #38a169;
}
```

### Exemple 2 : Style Carte

```css
.bringueuses-category-item {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.2s;
}

.bringueuses-category-item:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
```

### Exemple 3 : Gros Boutons

```css
.bringueuses-submit-btn {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.bringueuses-clear-btn {
    font-size: 15px;
    font-weight: 600;
}
```

## 🆘 Besoin d'Aide ?

Si vous avez besoin d'aide pour une personnalisation spécifique, vérifiez :
1. La console du navigateur (F12) pour les erreurs
2. Que vos modifications CSS sont bien appliquées (utilisez !important si nécessaire)
3. Que vous avez vidé le cache du navigateur

Bonne personnalisation ! 🎨
