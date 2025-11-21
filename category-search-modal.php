<?php
/**
 * Système de recherche par catégories avec popup modale (style Funbooker)
 * À ajouter dans functions.php de votre thème WordPress
 */

// Enqueue les scripts et styles personnalisés
function bringueuses_category_search_modal_assets() {
    // Enqueue le JavaScript
    wp_add_inline_script('jquery', bringueuses_get_modal_js());
}
add_action('wp_enqueue_scripts', 'bringueuses_category_search_modal_assets');

// Injecter le CSS directement dans le footer (solution fiable)
function bringueuses_inject_modal_css() {
    echo bringueuses_get_modal_css();
}
add_action('wp_footer', 'bringueuses_inject_modal_css', 999);

// Fonction pour obtenir le CSS de la modal
function bringueuses_get_modal_css() {
    return '
    <style>
        /* Overlay de la modal */
        .bringueuses-modal-overlay {
            display: none;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            z-index: 999998 !important;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bringueuses-modal-overlay.active {
            display: block !important;
            opacity: 1 !important;
        }

        /* Container de la modal */
        .bringueuses-category-modal {
            display: none;
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) scale(0.9);
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            background: white !important;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            z-index: 999999 !important;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .bringueuses-category-modal.active {
            display: block !important;
            opacity: 1 !important;
            transform: translate(-50%, -50%) scale(1) !important;
        }

        /* Header de la modal */
        .bringueuses-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 25px;
            border-bottom: 1px solid #e5e5e5;
        }

        .bringueuses-modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .bringueuses-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            transition: color 0.2s;
        }

        .bringueuses-modal-close:hover {
            color: #000;
        }

        /* Corps de la modal */
        .bringueuses-modal-body {
            max-height: calc(90vh - 180px);
            overflow-y: auto;
            padding: 20px 25px;
        }

        /* Catégorie principale */
        .bringueuses-category-item {
            margin-bottom: 15px;
        }

        .bringueuses-category-parent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            cursor: pointer;
            user-select: none;
        }

        .bringueuses-category-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            flex: 1;
        }

        .bringueuses-category-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin: 0;
            accent-color: #000000;
            flex-shrink: 0;
        }

        .bringueuses-category-label input[type="checkbox"]:checked + .bringueuses-category-name {
            color: #000000;
            font-weight: 700;
        }

        .bringueuses-category-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #000;
            cursor: pointer;
            user-select: none;
        }

        .bringueuses-category-name:hover {
            color: #c29948;
        }

        .bringueuses-category-count {
            background-color: #f0f0f0;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 500;
        }

        .bringueuses-category-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, background-color 0.2s ease;
            border-radius: 4px;
            width: 32px;
            height: 32px;
        }

        .bringueuses-category-toggle:hover {
            background-color: rgba(194, 153, 72, 0.1);
        }

        .bringueuses-category-toggle svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
            stroke: #666;
            stroke-width: 2;
            fill: none;
        }

        .bringueuses-category-toggle.expanded svg {
            transform: rotate(90deg);
            stroke: #c29948;
        }

        /* Sous-catégories */
        .bringueuses-subcategories {
            display: none;
            margin-left: 30px;
            margin-top: 8px;
        }

        .bringueuses-subcategories.expanded {
            display: block;
        }

        .bringueuses-subcategory-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .bringueuses-subcategory-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            flex: 1;
        }

        .bringueuses-subcategory-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin: 0;
            accent-color: #000000;
            flex-shrink: 0;
        }

        .bringueuses-subcategory-label input[type="checkbox"]:checked + .bringueuses-subcategory-name {
            color: #000000;
            font-weight: 600;
        }

        .bringueuses-subcategory-name {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
            cursor: pointer;
            user-select: none;
        }

        .bringueuses-subcategory-name:hover {
            color: #c29948;
        }

        /* Footer de la modal */
        .bringueuses-modal-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 25px;
            border-top: 1px solid #e5e5e5;
            background-color: #f8f9fa;
            border-radius: 0 0 8px 8px;
        }

        .bringueuses-clear-btn {
            background: none;
            border: none;
            color: #c29948;
            text-decoration: underline;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
        }

        .bringueuses-clear-btn:hover {
            color: #a67e3a;
        }

        .bringueuses-submit-btn {
            background-color: #c29948;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .bringueuses-submit-btn:hover {
            background-color: #a67e3a;
        }

        /* Masquer uniquement le Bootstrap Select original des catégories, pas notre bouton custom */
        #listeo-search-form_tax-listing_category .bootstrap-select:not(.bringueuses-custom-select) {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .bringueuses-category-modal {
                width: 95%;
                max-height: 95vh;
            }

            .bringueuses-modal-header,
            .bringueuses-modal-body,
            .bringueuses-modal-footer {
                padding: 15px;
            }

            .bringueuses-subcategories {
                margin-left: 20px;
            }
        }
    </style>
    ';
}

// Fonction pour obtenir le JavaScript de la modal
function bringueuses_get_modal_js() {
    return "
    jQuery(document).ready(function($) {
        console.log('=== BRINGUEUSES DEBUG ===');
        console.log('jQuery chargé:', typeof jQuery !== 'undefined');
        console.log('Nombre de #tax-listing_category trouvés:', $('#tax-listing_category').length);

        // ETAPE 1: Créer la modal une seule fois dans le DOM
        if (!$('#bringueuses-category-modal').length) {
            console.log('Création de la modal (première fois)');

            // Injecter la modal dans le body
            $('body').append('" . addslashes(bringueuses_get_modal_html()) . "');
            console.log('Modal injectée dans le body');

            // Variables
            var \$modal = $('#bringueuses-category-modal');
            var \$overlay = $('#bringueuses-modal-overlay');

            var \$closeBtn = $('.bringueuses-modal-close');
            var \$clearBtn = $('.bringueuses-clear-btn');
            var \$submitBtn = $('.bringueuses-submit-btn');
            var \$originalSelect = $('#tax-listing_category');

            console.log('Modal trouvée:', \$modal.length);
            console.log('Overlay trouvé:', \$overlay.length);
            console.log('Select original trouvé:', \$originalSelect.length);

            // SOLUTION: Cacher uniquement le Bootstrap Select des catégories et créer notre bouton
            console.log('Masquage du Bootstrap Select des catégories et création du bouton personnalisé');

            // Cacher uniquement le Bootstrap Select original du champ tax-listing_category
            var \$categoryContainer = $('#listeo-search-form_tax-listing_category');
            var \$originalBootstrapSelect = \$categoryContainer.find('.bootstrap-select:not(.bringueuses-custom-select)');

            // Récupérer le titre original du select pour l'universalité
            var originalTitle = \$originalSelect.attr('title') || 'Que recherchez-vous ?';
            console.log('Titre original du select:', originalTitle);

            \$originalBootstrapSelect.hide();
            \$categoryContainer.find('.dropdown-menu').hide();

            // Créer une structure complète Bootstrap Select pour apparence identique
            var customButtonHtml = '<div class=\"btn-group bootstrap-select show-tick bringueuses-custom-select\">' +
                '<button type=\"button\" class=\"btn dropdown-toggle bs-placeholder btn-default\" ' +
                'data-toggle=\"dropdown\" role=\"button\" title=\"' + originalTitle + '\">' +
                '<span class=\"filter-option pull-left\">' + originalTitle + '</span>&nbsp;' +
                '<span class=\"bs-caret\"><span class=\"caret\"></span></span>' +
                '</button>' +
                '</div>';

            // Insérer le bouton dans le container des catégories uniquement
            \$categoryContainer.prepend(customButtonHtml);
            console.log('Bouton personnalisé créé et inséré dans le container des catégories');

            var \$customBtn = \$categoryContainer.find('.bringueuses-custom-select .dropdown-toggle');

            // Clic sur le bouton personnalisé
            \$customBtn.on('click', function(e) {
                console.log('*** CLIC DETECTE SUR LE BOUTON PERSONNALISE ***');
                e.preventDefault();
                e.stopPropagation();

                // FORCER LES STYLES EN JAVASCRIPT (ultra-robuste)
                \$overlay.css({
                    'display': 'block',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'background-color': 'rgba(0, 0, 0, 0.5)',
                    'z-index': '999998',
                    'opacity': '1'
                });

                \$modal.css({
                    'display': 'block',
                    'position': 'fixed',
                    'top': '50%',
                    'left': '50%',
                    'transform': 'translate(-50%, -50%)',
                    'max-width': '800px',
                    'width': '90%',
                    'max-height': '90vh',
                    'background': 'white',
                    'border-radius': '8px',
                    'box-shadow': '0 10px 40px rgba(0, 0, 0, 0.2)',
                    'z-index': '999999',
                    'opacity': '1'
                });

                $('body').css('overflow', 'hidden');

                console.log('Modal ouverte avec styles forcés');
                console.log('Modal visible:', \$modal.is(':visible'));
                console.log('Overlay visible:', \$overlay.is(':visible'));
                console.log('Z-index modal:', \$modal.css('z-index'));
                console.log('Position modal:', \$modal.css('position'));

                return false;
            });
            console.log('Gestionnaire de clic attaché sur le bouton personnalisé');

            // Fermer la modal
            function closeModal() {
                \$overlay.css('display', 'none');
                \$modal.css('display', 'none');
                $('body').css('overflow', '');
                console.log('Modal fermée');
            }

            \$closeBtn.on('click', closeModal);
            \$overlay.on('click', closeModal);

            // Touche ESC pour fermer
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && \$modal.hasClass('active')) {
                    closeModal();
                }
            });

            // Rendre le titre de la catégorie cliquable pour cocher/décocher
            $('.bringueuses-category-name, .bringueuses-subcategory-name').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Trouver la checkbox associée
                var \$label = $(this).closest('label');
                var \$checkbox = \$label.find('input[type=\"checkbox\"]');

                // Inverser l'état de la checkbox
                \$checkbox.prop('checked', !\$checkbox.prop('checked'));

                // Déclencher manuellement l'événement change
                \$checkbox.trigger('change');

                console.log('Clic sur titre - Checkbox cochée:', \$checkbox.prop('checked'));
            });

            // Toggle des sous-catégories avec la flèche
            $('.bringueuses-category-toggle').on('click', function(e) {
                e.stopPropagation();
                var \$subcats = $(this).closest('.bringueuses-category-item').find('.bringueuses-subcategories');
                \$subcats.toggleClass('expanded');
                $(this).toggleClass('expanded');
            });

            // Gestion des checkboxes parent : déplier automatiquement quand on coche
            $('.bringueuses-category-parent input[type=\"checkbox\"]').on('change', function() {
                var \$parent = $(this).closest('.bringueuses-category-item');
                var \$subcats = \$parent.find('.bringueuses-subcategories');
                var \$toggle = \$parent.find('.bringueuses-category-toggle');

                // Si on coche la catégorie parent ET qu'il y a des sous-catégories
                if (this.checked && \$subcats.length > 0) {
                    // Déplier les sous-catégories
                    \$subcats.addClass('expanded');
                    \$toggle.addClass('expanded');
                }
                // Note: On ne coche PAS automatiquement les sous-catégories
            });

            // Gestion des sous-catégories : cocher le parent quand on coche un enfant
            $('.bringueuses-subcategory-label input[type=\"checkbox\"]').on('change', function() {
                var \$categoryItem = $(this).closest('.bringueuses-category-item');
                var \$parentCheckbox = \$categoryItem.find('.bringueuses-category-parent input[type=\"checkbox\"]');
                var \$allSubCheckboxes = \$categoryItem.find('.bringueuses-subcategory-label input[type=\"checkbox\"]');

                // Si on coche une sous-catégorie, cocher aussi le parent
                if (this.checked) {
                    \$parentCheckbox.prop('checked', true);
                } else {
                    // Si on décoche et qu'aucune autre sous-catégorie n'est cochée, décocher le parent
                    var anyChecked = \$allSubCheckboxes.filter(':checked').length > 0;
                    if (!anyChecked) {
                        \$parentCheckbox.prop('checked', false);
                    }
                }
            });

            // Effacer toutes les sélections
            \$clearBtn.on('click', function(e) {
                e.preventDefault();
                $('.bringueuses-category-modal input[type=\"checkbox\"]').prop('checked', false);
            });

            // Soumettre et fermer
            \$submitBtn.on('click', function(e) {
                e.preventDefault();

                // Récupérer toutes les catégories sélectionnées
                var selectedValues = [];
                $('.bringueuses-category-modal input[type=\"checkbox\"]:checked').each(function() {
                    var value = $(this).val();
                    var label = $(this).closest('label').find('span').first().text();
                    selectedValues.push(value);
                    console.log('Catégorie sélectionnée:', label, '- Valeur:', value);
                });

                console.log('=== VALEURS ENVOYÉES AU FORMULAIRE ===');
                console.log('Nombre total:', selectedValues.length);
                console.log('Valeurs:', selectedValues);

                // Mettre à jour le select original
                \$originalSelect.val(selectedValues);

                console.log('Select WordPress mis à jour avec:', \$originalSelect.val());

                // Mettre à jour le texte du bouton personnalisé (recherche dynamique)
                var \$currentCustomBtn = $('.bringueuses-custom-select .dropdown-toggle');
                var currentTitle = \$originalSelect.attr('title') || 'Que recherchez-vous ?';
                if (selectedValues.length > 0) {
                    \$currentCustomBtn.find('.filter-option').text(selectedValues.length + ' catégorie(s) sélectionnée(s)');
                } else {
                    \$currentCustomBtn.find('.filter-option').text(currentTitle);
                }

                // Déclencher l'événement change sur le select original pour que le formulaire de recherche fonctionne
                \$originalSelect.trigger('change');

                closeModal();
            });

            // Initialiser les valeurs déjà sélectionnées
            var currentValues = \$originalSelect.val() || [];
            if (currentValues.length > 0) {
                currentValues.forEach(function(value) {
                    $('.bringueuses-category-modal input[value=\"' + value + '\"]').prop('checked', true);
                });
                \$customBtn.find('.filter-option').text(currentValues.length + ' catégorie(s) sélectionnée(s)');
            }

            console.log('=== INITIALISATION TERMINEE ===');
        }

        // ETAPE 2: Initialiser le bouton custom (même si modal existe déjà)
        // Cette fonction peut être appelée plusieurs fois en toute sécurité
        function initCustomButton() {
            var \$categoryContainer = $('#listeo-search-form_tax-listing_category');

            if (!\$categoryContainer.length) {
                console.log('Pas de container de catégories sur cette page');
                return;
            }

            // Vérifier si le bouton custom existe déjà
            if (\$categoryContainer.find('.bringueuses-custom-select').length) {
                console.log('Bouton custom déjà existant, rien à faire');
                return;
            }

            console.log('=== CREATION DU BOUTON CUSTOM ===');

            var \$originalSelect = $('#tax-listing_category');
            if (!\$originalSelect.length) {
                console.log('Select #tax-listing_category introuvable');
                return;
            }

            var originalTitle = \$originalSelect.attr('title') || 'Que recherchez-vous ?';
            console.log('Titre:', originalTitle);

            // Masquer le Bootstrap Select original
            \$categoryContainer.find('.bootstrap-select:not(.bringueuses-custom-select)').hide();
            \$categoryContainer.find('.dropdown-menu').hide();

            // Créer le bouton custom
            var customButtonHtml = '<div class=\"btn-group bootstrap-select show-tick bringueuses-custom-select\">' +
                '<button type=\"button\" class=\"btn dropdown-toggle bs-placeholder btn-default\">' +
                '<span class=\"filter-option pull-left\">' + originalTitle + '</span>&nbsp;' +
                '<span class=\"bs-caret\"><span class=\"caret\"></span></span>' +
                '</button>' +
                '</div>';

            \$categoryContainer.prepend(customButtonHtml);
            console.log('Bouton custom créé');

            // Attacher l'événement de clic
            var \$customBtn = \$categoryContainer.find('.bringueuses-custom-select .dropdown-toggle');
            \$customBtn.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var \$modal = $('#bringueuses-category-modal');
                var \$overlay = $('#bringueuses-modal-overlay');

                // Forcer l'affichage de la modal
                \$overlay.css({
                    'display': 'block',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'background-color': 'rgba(0, 0, 0, 0.5)',
                    'z-index': '999998',
                    'opacity': '1'
                });

                \$modal.css({
                    'display': 'block',
                    'position': 'fixed',
                    'top': '50%',
                    'left': '50%',
                    'transform': 'translate(-50%, -50%)',
                    'max-width': '800px',
                    'width': '90%',
                    'max-height': '90vh',
                    'background': 'white',
                    'border-radius': '8px',
                    'box-shadow': '0 10px 40px rgba(0, 0, 0, 0.2)',
                    'z-index': '999999',
                    'opacity': '1'
                });

                $('body').css('overflow', 'hidden');
                console.log('Modal ouverte depuis bouton custom');
            });

            // Initialiser les valeurs déjà sélectionnées
            var currentValues = \$originalSelect.val() || [];
            if (currentValues.length > 0) {
                $('.bringueuses-category-modal input[type=\"checkbox\"]').prop('checked', false);
                currentValues.forEach(function(value) {
                    $('.bringueuses-category-modal input[value=\"' + value + '\"]').prop('checked', true);
                });
                \$customBtn.find('.filter-option').text(currentValues.length + ' catégorie(s) sélectionnée(s)');
                console.log('Valeurs pré-sélectionnées:', currentValues);
            }

            console.log('=== BOUTON CUSTOM PRET ===');
        }

        // Appeler l'initialisation du bouton custom
        initCustomButton();

        // Ré-initialiser après navigation AJAX (si le contenu change)
        $(document).ajaxComplete(function() {
            console.log('Ajax complete - vérification du bouton custom');
            setTimeout(function() {
                initCustomButton();
            }, 300);
        });
    });
    ";
}

// Fonction pour générer le HTML de la modal
function bringueuses_get_modal_html() {
    // Récupérer toutes les catégories parentes (on va filtrer manuellement)
    $categories = get_terms(array(
        'taxonomy' => 'listing_category',
        'hide_empty' => false, // On récupère tout pour calculer manuellement
        'parent' => 0, // Seulement les catégories parentes
    ));

    $html = '<div id="bringueuses-modal-overlay" class="bringueuses-modal-overlay"></div>';
    $html .= '<div id="bringueuses-category-modal" class="bringueuses-category-modal">';

    // Header
    $html .= '<div class="bringueuses-modal-header">';
    $html .= '<h3>Catégories</h3>';
    $html .= '<button class="bringueuses-modal-close" aria-label="Fermer">&times;</button>';
    $html .= '</div>';

    // Body
    $html .= '<div class="bringueuses-modal-body">';

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            // Récupérer les sous-catégories avec annonces uniquement
            $subcategories = get_terms(array(
                'taxonomy' => 'listing_category',
                'hide_empty' => true, // N'afficher que les sous-catégories avec annonces
                'parent' => $category->term_id,
            ));

            // Calculer le total : annonces de la catégorie parente + annonces des sous-catégories
            $total_count = intval($category->count);

            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                foreach ($subcategories as $subcat) {
                    $total_count += intval($subcat->count);
                }
            }

            // N'afficher la catégorie que si elle a au moins une annonce (parent ou enfants)
            if ($total_count === 0) {
                continue;
            }

            $html .= '<div class="bringueuses-category-item">';

            // Catégorie parente
            $html .= '<div class="bringueuses-category-parent">';
            $html .= '<label class="bringueuses-category-label">';
            $html .= '<input type="checkbox" value="' . esc_attr($category->slug) . '">';
            $html .= '<div class="bringueuses-category-name">';
            $html .= '<span>' . esc_html($category->name) . '</span>';
            $html .= '<span class="bringueuses-category-count">' . $total_count . '</span>';
            $html .= '</div>';
            $html .= '</label>';

            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                $html .= '<button class="bringueuses-category-toggle" aria-label="Afficher les sous-catégories">';
                $html .= '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                $html .= '<polyline points="9 6 15 12 9 18" stroke-linecap="round" stroke-linejoin="round"/>';
                $html .= '</svg>';
                $html .= '</button>';
            }

            $html .= '</div>'; // .bringueuses-category-parent

            // Sous-catégories
            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                $html .= '<div class="bringueuses-subcategories">';
                foreach ($subcategories as $subcat) {
                    $html .= '<div class="bringueuses-subcategory-item">';
                    $html .= '<label class="bringueuses-subcategory-label">';
                    $html .= '<input type="checkbox" value="' . esc_attr($subcat->slug) . '">';
                    $html .= '<div class="bringueuses-subcategory-name">';
                    $html .= '<span>' . esc_html($subcat->name) . '</span>';
                    $html .= '<span class="bringueuses-category-count">' . $subcat->count . '</span>';
                    $html .= '</div>';
                    $html .= '</label>';
                    $html .= '</div>';
                }
                $html .= '</div>'; // .bringueuses-subcategories
            }

            $html .= '</div>'; // .bringueuses-category-item
        }
    } else {
        $html .= '<p>Aucune catégorie disponible.</p>';
    }

    $html .= '</div>'; // .bringueuses-modal-body

    // Footer
    $html .= '<div class="bringueuses-modal-footer">';
    $html .= '<button class="bringueuses-clear-btn">Effacer</button>';
    $html .= '<button class="bringueuses-submit-btn">Sélectionner</button>';
    $html .= '</div>';

    $html .= '</div>'; // .bringueuses-category-modal

    return $html;
}

// Hook pour régénérer la modal avec AJAX (optionnel, pour mise à jour dynamique)
function bringueuses_refresh_category_modal() {
    check_ajax_referer('bringueuses_modal_nonce', 'nonce');

    wp_send_json_success(array(
        'html' => bringueuses_get_modal_html()
    ));
}
add_action('wp_ajax_bringueuses_refresh_modal', 'bringueuses_refresh_category_modal');
add_action('wp_ajax_nopriv_bringueuses_refresh_modal', 'bringueuses_refresh_category_modal');
