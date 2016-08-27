/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    
    updateMonthName();
        
    $(document).on("click", "#add-consommation", function(e){

        var prototype = $(this).data("prototype");
        var numRow = $(".consommation-month").length;
        
        if (numRow > 11) {
            alert('Vous ne pouvez pas ajouter plus de 12 lignes de consommation');
            return false;
        }
        
        prototype = prototype.replace(/__name__/g, numRow);
        var id = $(prototype).find(".consommation-month:first").attr('id');
        
        $(".odd-form-conteneur").append(prototype);
        
        renumberMonth();
        
        return false;
    });
    
    $(document).on("click", ".deleteRow", function(e){
        e.preventDefault();

        $(this).parents("li").remove();
        renumberMonth();
        
        return false;
    });
});

function updateMonthName()
{
    var months = {
        1: 'Janvier',
        2: 'Février',
        3: 'Mars',
        4: 'Avril',
        5: 'Mai',
        6: 'Juin',
        7: 'Juillet',
        8: 'Août',
        9: 'Septembre',
        10: 'Octobre',
        11: 'Novembre',
        12: 'Décembre',
    };
    
    $(".consommation-month").each( function(index) {
        $(this).siblings("label").html(months[$(this).val()]);
    });
    
}

function renumberMonth()
{
    $(".consommation-month").each( function(index) {
        $(this).val(index + 1);
    });
    
    updateMonthName();
}