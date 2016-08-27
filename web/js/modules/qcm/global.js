$(document).ready(function () {
    
    $(document).on("click", "#add-etape", function(e) {
        e.preventDefault();
        
        var list   = $(this).siblings(".etape-container:last");
        var nb     = list.find("> li").length;
        
        list.append( addEtape(nb, 0, 0) );
        
        return false;
    });
    
    $(document).on("click", ".add-question", function(e){
        e.preventDefault();
        
        var indexEtape      = $(this).parents(".etape-item").attr('data-index');
        var list            = $(this).siblings(".question-container:last");
        var indexQuestion   = list.find("> li").length;
        
        list.append( addQuestion(indexEtape, indexQuestion, 0) );
        
        return false;
    });
    
    $(document).on("click", ".add-reponse", function(e){
        e.preventDefault();
        
        var indexEtape      = $(this).parents(".etape-item").attr('data-index');
        var indexQuestion   = $(this).parents(".question-item").attr('data-index');
        var list            = $(this).siblings(".reponse-container:last");
        var indexReponse    = list.find("> li").length;
        
        list.append( addReponse(indexEtape, indexQuestion, indexReponse) );
        
        return false;
    });
});

function getPrototype(name)
{
    return $(".etape-container:first").attr('data-prototype-' + name);
}



function addEtape(indexEtape, indexQuestion, indexReponse)
{
    var prototype = getPrototype('etape');
    
    prototype = prototype.replace(/__name__/g, indexEtape);
    prototype = prototype.replace(/__INDEX_ETAPE__/g, indexEtape);
    prototype = prototype.replace(/__NUM_ETAPE__/g, indexEtape + 1);
    
    return prototype.replace( /__QUESTION_CONTAINER__/, addQuestion(indexEtape, indexQuestion, indexReponse) );
}

function addQuestion(indexEtape, indexQuestion, indexReponse)
{
    var occurrence = 1;
    var prototype = getPrototype('question');
    
    prototype = prototype.replace(/__INDEX_QUESTION__/g, indexQuestion);
    prototype = prototype.replace(/(__name__)/g, function(match, contents, offset, s)
        {
            if (occurrence === 1) {
                occurrence++;
                return indexEtape;
            } else {
                occurrence = 1;
                return indexQuestion;
            }
        }
    );
    
    return prototype.replace( /__REPONSE_CONTAINER__/, addReponse(indexEtape, indexQuestion, indexReponse) );
}

function addReponse(indexEtape, indexQuestion, indexReponse)
{
    var occurrence = 1;
    var prototype = getPrototype('reponse');
    
    prototype = prototype.replace(/__INDEX_REPONSE__/g, indexReponse);
    return prototype.replace(/(__name__)/g, function(match, contents, offset, s)
        {
            if (occurrence === 1) {
                occurrence++;
                return indexEtape;
            } else if (occurrence === 2) {
                occurrence++;
                return indexQuestion;
            } else {
                occurrence = 1;
                return indexReponse;
            }
        }
    );
}



/*
function copyBlock(source, target)
{
    // Vidange des champs
    $(source).find('input')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

    // On ne garde qu'une question et une réponse par étape.
    $(source).find('.question-container:not(:first)').remove();
    $(source).find('.reponse-container:not(:first)').remove();
    
    // Ajout du noeud dans le DOM
    $(source).insertAfter(target);
        
    reNumberingAttribute();
}

function reNumberingAttribute()
{
    $($('.etape-container')).each(function(etapeIndex){
        var etape = $(this);
        
        changeAttribute(
                $(etape).find('input'), 
                /QcmForm_etapes_\d+_libelle/, 
                'id', 
                'QcmForm_etapes_' + etapeIndex + '_libelle'
        );
        changeAttribute(
            $(etape).find('input'), 
            /QcmForm[etapes][\d+][libelle]/, 
            'name', 
            'QcmForm[etapes][' + etapeIndex + '][libelle]'
        );
        
        $($(etape).find('.question-container')).each(function(questionIndex){
            var question = $(this);

            changeAttribute(
                $(question).find('input'), 
                /QcmForm_etapes_\d+_questions_\d+_libelle/, 
                'id', 
                'QcmForm_etapes_' + etapeIndex + '_questions_' + questionIndex + '_libelle'
            );
            changeAttribute(
                $(question).find('input'), 
                /QcmForm[etapes][\d+][libelle]/, 
                'name', 
                'QcmForm[etapes][' + etapeIndex + '][libelle]'
            );
        
            // Modification des id des champs étape
            changeAttribute(source, regex, attribute, value)
            $(etape).find('input, select').filter(function() {
                    return $(this).attr('id').match(/QcmForm_etapes_\d+_questions_\d+_libelle/);
                })
                .attr('id', '');

            // Modification des names des champs étape
            changeAttribute(source, regex, attribute, value)
            $(etape).find('input').filter(function() {
                    return $(this).attr('name').match(/QcmForm[etapes][\d+][questions][\d+][libelle]/);
                })
                .attr('id', 'QcmForm[etapes][' + etapeIndex + '][questions]' + questionIndex + '[libelle]');
        });
    });
}

function reNumbering(elements, libelle)
{
    $(elements).each(function(i){
        i++;
        $(this).html(libelle + i);
    });
}

function changeAttribute(source, regex, attribute, value)
{
    $(source).filter(function() {
        return $(this).attr(attribute).match(regex);
    }).attr(attribute, value);
}
*/