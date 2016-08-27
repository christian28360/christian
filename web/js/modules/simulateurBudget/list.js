$(document).ready(function () {
    
    $('#supprimerSimulation').on('show.bs.modal', function (e) {
        
        $('#formSupprimerSimulation').get(0).setAttribute('action', $(e.relatedTarget).data('id'));
    });
    
    $('#validerSimulation').on('show.bs.modal', function (e) {
        
        $('#formValiderSimulation').get(0).setAttribute('action', $(e.relatedTarget).data('id'));
    });
});