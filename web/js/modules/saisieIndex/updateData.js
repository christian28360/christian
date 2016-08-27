$(document).ready(function() {

    $(document).on("keydown", "input", function(e) {
        if (e.keyCode === 13) { //Enter keycode
            $('input:focus').blur().change(); //The blur is to prevent change happen again
        }
    });

    /*
     # Méthode permettant la gestion des couleurs lors de la modification
     # des champs de saisies pour les champs non modifiable.
     */
    $("body").on("customColorableTD", ".colorable", function(event) {
        // Gère la couleur des champs de saisies
        if ($(this).data('source').trim !== $(this).html().trim) {
            $(this).addClass('danger');
        } else {
            $(this).removeClass('danger');
        }
    });

    /*
     # Méthode permettant la gestion des couleurs lors de la modification
     # des champs de saisies pour les champs modifiable.
     */
    $("body").on("customColorableINPUT", ".alterable", function(event) {
        // Permet de formater la valeur lors de la modification
        $(this).val($(this).val().replace(/\s/g, ''));

        // Gère la couleur des champs de saisies
        if ($(this).data('source') != $(this).val()) {
            $(this).parent('td').addClass('danger').removeClass('yellow');
        } else {
            $(this).parent('td').addClass('yellow').removeClass('danger');
        }

 //       $(this).formatNumber({format: $(this).data('mask'), locale: "fr"});
    });

    /*
     # Exécution de la gestion des couleurs au chargement de la page
     */
    $('.alterable').trigger('customColorableINPUT');
    $('.colorable').trigger('customColorableTD');
    /*
     # Enregistrement des données lors de la modification des champs
     # de saisies.
     */
    $("body").on("change", ".dataAlterable", function(event) {
        $('body').css('cursor', 'wait');
        // Envoi de la mise à jour au serveur
        $.ajax({
            dataType: "json",
            method: "post",
            cache: false,
            url: "{{ path('saisie-km-ajax-update-data', { 'immat' : saisie.immat }) }}",
            data: {
                'immat': $(this).data('immat'),
                'attribut': $(this).data('attribut'),
                'value': $(this).val()
            },
            success: function(data) {
                if (data.ETAT === 'OK') {
                    $('#indexNouveau-' + $(that).data('immat')).replaceWith(data.DATA.IMMAT);
                } else {
                    // on remet l'ancienne valeur
                    that.val($(that).data('source-old'));
                    // message d'alerte pour saisie erronnée
                    $('#error-update .modal-body').html(data.MESSAGE);
                    $('#error-update').modal('show');
                }
                /*
                 $("[id$='-" + $(that).data('dha') + "'] .alterable").trigger('customColorableINPUT');
                 $("[id$='-" + $(that).data('dha') + "'] .colorable").trigger('customColorableTD');
                 $('body').css('cursor', 'auto');
                 */
            }
        });
    });

});
