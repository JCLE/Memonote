
var pathAjax = Routing.generate('jclememo_ajax_search');
//var pathSearch = Routing.generate('jclememo_search');
//var pathImg = Routing.generate('jclememo_ajax_image');


/**
 * Autocompletion par recherche dans la BDD 
 * $.parseJSON()
 */
$( "#recherche" ).autocomplete({
   minLength: 2
  ,source: function(req, add)
  {
      $.ajax({
          url:pathAjax,
          type:"post",
          datatype:'json',
          timeout:5000,
          data:{
                recherche : $('#recherche').val()
                //,autreValeur : 'valeurTest'
               },
          cache:true,
          success: function(data){                  
              add($.map(data, function(item) {
                    return {
                        value : item.value,
                        desc : item.desc,
                        icon : item.icon,
                        label : item.label
                    }
                }));
            }
      });
  }
  ,focus: function( event, ui ) {}
})
// Selection auto du premier element
.autocomplete( "option", "autoFocus", true )
// Instancie pour chaque retour les element de l'autocompletion
.autocomplete( "instance" )._renderItem = function( ul, item) {
            return $( "<li>" )
//    .append( "<a><img src=\"/Symfony/web/"+item.icon+"\" /> " +  item.label +"</a>" )
    .append( "<a><img src=\""+pathImgDirectory+item.icon+".png\" /> " +  item.label +"</a>" )
    .appendTo( ul );
};


// **************  Partie soumission du formulaire ***********************

// La soumission du formulaire desactive l'autocompletion
$('#form_search').on('submit',function ()
{ 
    $( "#recherche" ).autocomplete({ disabled: true });
    
});

 // Lors de l'appui sur le bouton de recherche
$(".btn-search").on("click",function(){
    $('#form_search').submit();
    $('#recherche').focus();
//    $("#recherche").prop("disabled",true);
//    $(".btn-search").attr("disabled","disabled");
});



/**
 * Controle qu'un texte ne soit pas vide
 * @param {type} text = le texte à verifier
 * @returns {Boolean} = faux si chaine vaut une longueur de zéro
 */
function control(text)
{
    if(text.length == 0)
    {
        alert('La zone de saisie ne peut rester vide');
        return false;
    }
    else
    {
        return true;
    }
    
}

//$('#form_search').submit(function(){
//        $(this).prop("disabled",true);
////    $('#recherche').prop("disabled",true);
////    $(".btn-search").attr("disabled","disabled");
//});

// Lors de l'appui sur la touche entrée
//$( "#recherche" ).keypress(function(event) {
//    if(event.keyCode === 13)
//    {
//        if($('#form_search').submit())
//        {
//            $(this).prop("disabled",true);
//            $(".btn-search").attr("disabled","disabled");
//        }
//    }
//});


// ************** Fin Partie soumission formulaire *************************