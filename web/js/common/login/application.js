/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    var rolesList = $('.odd-form-conteneur');

    rolesList.sortable({
        handle: '.move-handler',
        update: function() {
            renumberRoles();
        }
    });
    
    $(document).on("click", "#add-roles", function(e){

        var prototype = $(this).data("prototype");
        var numRow = $(".input-level").length;
        prototype = prototype.replace(/__name__/g, numRow);

        $(".odd-form-conteneur").append(prototype);
        
        renumberRoles();
        
        return false;
    });
    
    $(document).on("click", ".deleteRow", function(e){
        e.preventDefault();

        $(this).parents("li").remove();
        
        renumberRoles();

        return false;
    });
});

function renumberRoles()
{
    $('.input-level').each(function(i) {
        $(this).val(i + 1);
    });
}