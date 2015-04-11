


$(document).ready(function(){

    // Disparition auto du message flash renvoyé par le controleur
    $('.flash_message').delay(6000).slideUp(1000);
    
    if($("#jcle_memobundle_note_description").length) // S'il existe
    {
        $("#jcle_memobundle_note_description").markItUp(mySettings,{
            resizeHandle : true
        }) // Shif + Tab pour quitter zone de texte sans la souris
        .keyup(function (e) {
            if (e.which === 9 && e.ctrlKey) {
                $('#jcle_memobundle_note_tag').focus();
            }
            if (e.which === 9 && e.shiftKey) {
                $('#jcle_memobundle_note_titre').focus();
            }
    });
    }
      
//      var ext = $('#jcle_memobundle_iconfile_fichier').val().split('.').pop().toLowerCase();
//        if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
//            alert('invalid extension!');
//        }
//    
//    console.log($('.panel-heading').width());
//    
//    $( window ).resize(function() {
//        console.log('panel : '+$('.panel-heading').width());
//        console.log('img user : '+$('.img-user').width());
//      });

//    if($('.img-user').width()>$('.panel-heading').width())
//    {
//        $(this).addClass('col-lg-12');
//    }
    
  });  

// *************************** Partie Chargement d'icones  *************************** 

$('.target-img').change(function()
{
    ChargeImg();
});

function ChargeImg ()
{
    var timestamp = Math.round(+new Date() / 1000);
    $('#recept-img').html('<img id=\"icon\" src="'+pathImgDirectory+$('.target-img').val()+'.png?time='+timestamp+'" alt="'+$('.target-img').val()+'" /></img>');
}

$('.icon-edit').click(function()
{
    $('#icon-actuel').hide();
    $('#icon-change').show();   
});

$(window).load(function()
{
    // Charge icone dans la page de création/modification des notes
    ChargeImg();
    // Envoi d'un tableau de toutes les images de la page a redimensionner
    resizeImage($('.resize-image'));
});


//  *************************** Fin Partie chargement icones  *************************** 


/**
 * Redimensionne (ou défini juste un format responsive) les images du tableau
 * si la dimension dépasse celle du panel-heading
 * @param {type} images
 */
function resizeImage(images)
{
    for (var i=0;i<images.length;i++)
    {
        if($(images[i]).width()>$('.panel-heading').width())
        {
            $(images[i]).addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12');
        }
        else
        {
            $(images[i]).addClass('img-responsive');
        }
    }
}


/**
 * Réinitialise un input à selection multiple
 * @param {type} aSelect = l'élément à sélection multiple
 */
function ResetSelect(aSelect)
{
    $(aSelect).find("option").removeAttr("selected");  
}


/**
 * Affiche un message de confirmation et redirige l'utilisateur en cas de validation
 * @param {type} aPath = adresse url d'envoi si confirmation
 * @param {type} aMessage = question posée à l'utilisateur
 */
function ConfirmDelete (aPath, aMessage)
{
    if(confirm(aMessage)) //Voulez-vous vraiment supprimer
    window.location.href=aPath;
}

/**
 * Genere une route par le biais de FosJSBundle avec l'id en passage de valeur
 * @param {type} aRoute = le nom de la route
 * @param {type} aValue = la valeur de l'id
 */
function GenererRoute (aRoute,aValue)
{
    window.location.href=Routing.generate( aRoute,{ id : aValue } );
}
//
//    jQuery.validator.setDefaults({
//        debug: true,
//        success: "valid"
//      });
//      $( "#jcle_memobundle_iconfile_fichier" ).validate({
//        rules: {
//          field: {
//            required: true,
//            extension: "png"
//          }
//        }
//      });