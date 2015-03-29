


$(document).ready(function(){

    // Disparition auto du message flash renvoyé par le controleur
    $('.flash_message').delay(2000).slideUp(1000);
    
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

$(window).load(function()
{
    ChargeImg();
//    $('#icon-change').hide(); 
});

$('.target-img').change(function()
{
    ChargeImg();
});

function ChargeImg ()
{
    $('#recept-img').html('<img id=\"icon\" src="'+pathImgDirectory+$('.target-img').val()+'.png" alt="'+$('.target-img').val()+'" /></img>');
}

$('.icon-edit').click(function()
{
    $('#icon-actuel').hide();
    $('#icon-change').show();   
});

function resizeImage(image)
{
    if($(image).width()>$('.panel-heading').width())
    {
        $(image).addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12');
    }
    else
    {
        $(image).addClass('img-responsive');
    }
}

//  *************************** Fin Partie chargement icones  *************************** 



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


//$('#icon-edit').on('click',function ()
//{ 
//    alert('clické');
//});

//var route = "{{ path('post_display', { 'id': PLACEHOLDER }) }}";
//window.location = route.replace("PLACEHODER", js_variable);


//$(document).ready(function() {
//
//    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
//    var $container = $('div#jcle_memobundle_note_notes');
//
//    // On ajoute un lien pour ajouter une nouvelle catégorie
//    var $addLink = $('<a href="#" id="add_link" class="btn btn-default">Ajouter un lien</a>');
//    $container.append($addLink);
//
//    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
//    $addLink.click(function(e) {
//      addCategory($container);
//      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
//      return false;
//    });
//
//    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
//    var index = $container.find(':input').length;
//
//    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
//    if (index == 0) {
//      addCategory($container);
//    } else {
//      // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
//      $container.children('div').each(function() {
//        addDeleteLink($(this));
//      });
//    }
//
//    // La fonction qui ajoute un formulaire Categorie
//    function addCategory($container) {
//      // Dans le contenu de l'attribut « data-prototype », on remplace :
//      // - le texte "__name__label__" qu'il contient par le label du champ
//      // - le texte "__name__" qu'il contient par le numéro du champ
//      var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Lien n°' + (index+1))
//          .replace(/__name__/g, index));
//
//      // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
//      addDeleteLink($prototype);
//
//      // On ajoute le prototype modifié à la fin de la balise <div>
//      $container.append($prototype);
//
//      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
//      index++;
//    }
//
//    // La fonction qui ajoute un lien de suppression d'une catégorie
//    function addDeleteLink($prototype) {
//      // Création du lien
//      $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');
//
//      // Ajout du lien
//      $prototype.append($deleteLink);
//
//      // Ajout du listener sur le clic du lien
//      $deleteLink.click(function(e) {
//        $prototype.remove();
//        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
//        return false;
//      });
//    }
//  });