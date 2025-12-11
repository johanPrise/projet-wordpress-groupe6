/**
 * JavaScript pour Comment Shop - Témoignages Clients
 * Gère : étoiles interactives + soumission AJAX
 */

jQuery(document).ready(function($) {
    
    // ============================================
    // SYSTÈME D'ÉTOILES INTERACTIF
    // ============================================
    
    $('.star-select').on('click', function() {
        var value = $(this).data('value');
        var $input = $(this).prev('input');
        
        // Cocher le radio caché
        $input.prop('checked', true);
        
        // Colorier étoiles
        $('.star-select').css('color', '#ddd');
        for (var i = 1; i <= value; i++) {
            $('[data-value="' + i + '"]').css('color', '#FFD700');
        }
        
        // Texte dynamique
        var labels = {
            1: '⭐ Décevant',
            2: '⭐⭐ Passable',
            3: '⭐⭐⭐ Bien',
            4: '⭐⭐⭐⭐ Très bien',
            5: '⭐⭐⭐⭐⭐ Excellent'
        };
        $('#rating-text').text(labels[value]).css('color', '#FFD700');
    });
    
    // Effet hover étoiles
    $('.star-select').on('mouseenter', function() {
        var value = $(this).data('value');
        $('.star-select').css('color', '#ddd');
        for (var i = 1; i <= value; i++) {
            $('[data-value="' + i + '"]').css('color', '#FFD700');
        }
    });
    
    $('.rating-stars').on('mouseleave', function() {
        // Recolorier selon sélection
        var checkedValue = $('input[name="rating"]:checked').val();
        $('.star-select').css('color', '#ddd');
        if (checkedValue) {
            for (var i = 1; i <= checkedValue; i++) {
                $('[data-value="' + i + '"]').css('color', '#FFD700');
            }
        }
    });
    
    // ============================================
    // SOUMISSION AJAX DU FORMULAIRE
    // ============================================
    
    $('#form-avis-ajax').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $button = $form.find('button[type="submit"]');
        var buttonText = $button.text();
        
        // Désactiver bouton
        $button.prop('disabled', true).text('Envoi en cours...');
        
        // Supprimer anciens messages
        $('.notice').remove();
        
        // Préparer données
        var formData = {
            action: 'submit_avis',
            nonce: commentShopAjax.nonce,
            client_name: $('#client_name').val(),
            client_email: $('#client_email').val(),
            rating: $('input[name="rating"]:checked').val(),
            avis_comment: $('#avis_comment').val(),
            product_id: $('#product_select').val() || 0
        };
        
        // Validation basique
        if (!formData.client_name || !formData.client_email || !formData.rating || !formData.avis_comment) {
            $form.prepend('<div class="notice notice-error"><p>❌ Veuillez remplir tous les champs obligatoires.</p></div>');
            $button.prop('disabled', false).text(buttonText);
            return;
        }
        
        // Requête AJAX
        $.ajax({
            url: commentShopAjax.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Message succès
                    $form.prepend('<div class="notice notice-success"><p>✅ ' + response.data.message + '</p></div>');
                    
                    // Réinitialiser formulaire
                    $form[0].reset();
                    $('.star-select').css('color', '#ddd');
                    $('#rating-text').text('');
                    
                    // Scroll vers message
                    $('html, body').animate({
                        scrollTop: $form.offset().top - 100
                    }, 500);
                } else {
                    $form.prepend('<div class="notice notice-error"><p>❌ ' + response.data.message + '</p></div>');
                }
            },
            error: function() {
                $form.prepend('<div class="notice notice-error"><p>❌ Erreur réseau. Veuillez réessayer.</p></div>');
            },
            complete: function() {
                $button.prop('disabled', false).text(buttonText);
            }
        });
    });
});
