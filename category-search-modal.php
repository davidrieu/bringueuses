<?php
/**
 * Système de recherche par catégories avec popup modale (style Funbooker)
 * À ajouter dans functions.php de votre thème WordPress
 */

// Enqueue les scripts et styles personnalisés
function bringueuses_category_search_modal_assets() {
    // Enqueue le CSS
    wp_add_inline_style('wp-admin', bringueuses_get_modal_css());

    // Si vous avez un style frontend, utilisez plutôt :
    // wp_enqueue_style('bringueuses-modal', get_stylesheet_directory_uri() . '/css/category-modal.css');

    // Enqueue le JavaScript
    wp_add_inline_script('jquery', bringueuses_get_modal_js());
}
add_action('wp_enqueue_scripts', 'bringueuses_category_search_modal_assets');

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
            accent-color: #007bff;
        }

        .bringueuses-category-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #000;
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
            padding: 5px;
            font-size: 14px;
            color: #666;
            transition: transform 0.3s;
        }

        .bringueuses-category-toggle.expanded {
            transform: rotate(90deg);
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
            accent-color: #007bff;
        }

        .bringueuses-subcategory-name {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
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
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
        }

        .bringueuses-clear-btn:hover {
            color: #0056b3;
        }

        .bringueuses-submit-btn {
            background-color: #007bff;
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
            background-color: #0056b3;
        }

        /* Masquer complètement Bootstrap Select */
        .select-taxonomy .bootstrap-select {
            display: none !important;
        }

        /* Bouton personnalisé */
        .bringueuses-custom-trigger {
            width: 100%;
            padding: 10px 15px;
            text-align: left;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .bringueuses-custom-trigger:hover {
            border-color: #999;
        }

        .bringueuses-custom-trigger:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .bringueuses-custom-trigger .filter-option {
            flex: 1;
        }

        .bringueuses-custom-trigger .caret {
            margin-left: 10px;
            border-top: 4px solid #333;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
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
        console.log('Nombre de .select-taxonomy trouvés:', $('.select-taxonomy').length);

        // Créer et injecter la modal dans le DOM
        if ($('.select-taxonomy').length && !$('#bringueuses-category-modal').length) {
            console.log('Condition validée - Injection de la modal');

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

            // SOLUTION: Cacher Bootstrap Select et créer notre propre bouton
            console.log('Masquage de Bootstrap Select et création du bouton personnalisé');

            // Cacher complètement Bootstrap Select
            $('.select-taxonomy .bootstrap-select').hide();
            $('.select-taxonomy .dropdown-menu').hide();

            // Créer notre propre bouton
            var customButton = $('<button>', {
                type: 'button',
                class: 'bringueuses-custom-trigger btn btn-default',
                html: '<span class=\"filter-option pull-left\">Que recherchez-vous ?</span> <span class=\"caret\"></span>',
                css: {
                    width: '100%',
                    padding: '10px 15px',
                    textAlign: 'left',
                    backgroundColor: 'white',
                    border: '1px solid #ccc',
                    borderRadius: '4px',
                    cursor: 'pointer',
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center'
                }
            });

            // Insérer le bouton dans le premier .select-taxonomy qui contient le select #tax-listing_category
            var \$targetContainer = \$originalSelect.closest('.select-taxonomy');
            if (\$targetContainer.length === 0) {
                \$targetContainer = $('.select-taxonomy').first();
            }
            \$targetContainer.prepend(customButton);
            console.log('Bouton personnalisé créé et inséré');

            var \$customBtn = $('.bringueuses-custom-trigger');

            // Clic sur le bouton personnalisé
            \$customBtn.on('click', function(e) {
                console.log('*** CLIC DETECTE SUR LE BOUTON PERSONNALISE ***');
                e.preventDefault();
                e.stopPropagation();

                // Ouvrir la modal
                \$overlay.addClass('active');
                \$modal.addClass('active');
                $('body').css('overflow', 'hidden');
                console.log('Modal ouverte');
                console.log('Modal visible:', \$modal.is(':visible'));
                console.log('Overlay visible:', \$overlay.is(':visible'));

                // Debug CSS
                console.log('Z-index modal:', \$modal.css('z-index'));
                console.log('Z-index overlay:', \$overlay.css('z-index'));
                console.log('Position modal:', \$modal.css('position'));
                console.log('Top modal:', \$modal.css('top'));
                console.log('Left modal:', \$modal.css('left'));
                console.log('Display modal:', \$modal.css('display'));
                console.log('Opacity modal:', \$modal.css('opacity'));

                return false;
            });
            console.log('Gestionnaire de clic attaché sur le bouton personnalisé');

            // Fermer la modal
            function closeModal() {
                \$overlay.removeClass('active');
                \$modal.removeClass('active');
                $('body').css('overflow', '');
            }

            \$closeBtn.on('click', closeModal);
            \$overlay.on('click', closeModal);

            // Touche ESC pour fermer
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && \$modal.hasClass('active')) {
                    closeModal();
                }
            });

            // Toggle des sous-catégories
            $('.bringueuses-category-toggle').on('click', function(e) {
                e.stopPropagation();
                var \$subcats = $(this).closest('.bringueuses-category-item').find('.bringueuses-subcategories');
                \$subcats.toggleClass('expanded');
                $(this).toggleClass('expanded');
            });

            // Gestion des checkboxes parent
            $('.bringueuses-category-parent input[type=\"checkbox\"]').on('change', function() {
                var \$parent = $(this).closest('.bringueuses-category-item');
                var \$subcatCheckboxes = \$parent.find('.bringueuses-subcategories input[type=\"checkbox\"]');
                \$subcatCheckboxes.prop('checked', this.checked);
            });

            // Gestion des checkboxes enfant
            $('.bringueuses-subcategory-label input[type=\"checkbox\"]').on('change', function() {
                var \$parent = $(this).closest('.bringueuses-category-item');
                var \$parentCheckbox = \$parent.find('.bringueuses-category-parent input[type=\"checkbox\"]');
                var \$subcatCheckboxes = \$parent.find('.bringueuses-subcategories input[type=\"checkbox\"]');

                // Si tous les enfants sont cochés, cocher le parent
                var allChecked = \$subcatCheckboxes.length === \$subcatCheckboxes.filter(':checked').length;
                \$parentCheckbox.prop('checked', allChecked);
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
                    selectedValues.push($(this).val());
                });

                // Mettre à jour le select original
                \$originalSelect.val(selectedValues);

                // Mettre à jour le texte du bouton personnalisé
                if (selectedValues.length > 0) {
                    \$customBtn.find('.filter-option').text(selectedValues.length + ' catégorie(s) sélectionnée(s)');
                } else {
                    \$customBtn.find('.filter-option').text('Que recherchez-vous ?');
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
        } else {
            console.error('ERREUR: Conditions non remplies');
            console.log('.select-taxonomy existe:', $('.select-taxonomy').length > 0);
            console.log('Modal déjà présente:', $('#bringueuses-category-modal').length > 0);
        }
    });
    ";
}

// Fonction pour générer le HTML de la modal
function bringueuses_get_modal_html() {
    // Récupérer les catégories (taxonomy 'listing_category')
    $categories = get_terms(array(
        'taxonomy' => 'listing_category',
        'hide_empty' => false,
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
            $html .= '<div class="bringueuses-category-item">';

            // Catégorie parente
            $html .= '<div class="bringueuses-category-parent">';
            $html .= '<label class="bringueuses-category-label">';
            $html .= '<input type="checkbox" value="' . esc_attr($category->slug) . '">';
            $html .= '<div class="bringueuses-category-name">';
            $html .= '<span>' . esc_html($category->name) . '</span>';
            $html .= '<span class="bringueuses-category-count">' . $category->count . '</span>';
            $html .= '</div>';
            $html .= '</label>';

            // Récupérer les sous-catégories
            $subcategories = get_terms(array(
                'taxonomy' => 'listing_category',
                'hide_empty' => false,
                'parent' => $category->term_id,
            ));

            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                $html .= '<button class="bringueuses-category-toggle" aria-label="Afficher les sous-catégories">▶</button>';
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
    $html .= '<button class="bringueuses-submit-btn">Afficher les résultats</button>';
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
