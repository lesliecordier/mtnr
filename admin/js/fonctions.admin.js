function verifInput(input){
    var vide = true,
    retour;
    $.each($('input[name*="'+input+'"]'), function(index, value){
        if(value.value !== ""){
            vide = false;
            return false;
        }
    });
    if(vide){
        $('input[name*="'+input+'"]').next().addClass('erreur');
        $('input[name*="'+input+'"]').addClass('erreur');
        $('#th'+input).addClass('erreur');
        $('#lbl'+input).addClass('erreur');
        retour = false;
    }else{
        $('input[name*="'+input+'"]').next().removeClass('erreur');
        $('input[name*="'+input+'"]').removeClass('erreur');
        $('#th'+input+'').removeClass('erreur');
        $('#lbl'+input).removeClass('erreur');
    }
    return retour;
}




$(function(){

    /***********************
     *      Sommaire       *
     ***********************/ 
    
    // Menu accordéon 
    $('#liste ul').hide();
    $('.panel-heading').on('click', function(){
        $(this).parent().toggleClass('panel-default').toggleClass('panel-info');
        $(this).next().slideToggle();
    });
    
    //Tri liste select
    $('.form-inline select').on('change', function(){
        var choix = $('.form-inline').serialize(),
        categorie = $(this).parent().parent().parent().attr('id');
        categorie = categorie.substr(7).toLowerCase();
        
        //réinitialisation si les selects sont vides
        var url;
        switch(choix){
            case 'region=&type=&domaine=':{
                url ="admin.php?control=vins&action=gestion";
                break;
            }
            case 'pays=&couleur=':{
                url = "admin.php?control=bieres&action=gestion";
                break;
            }
            case 'alcool=':{
                url = "admin.php?control=premiums&action=gestion"; 
                break;
            }
            case 'date=':{
                url = "admin.php?control=offres&action=gestion"; 
                break;
            }
        }
        $(location).attr('href',url);
        
        //mise à jour des selects
        $.ajax({
            type: "POST",
            url: "admin.php?control="+categorie+"&action=select",
            data: choix,
            dataType: 'json',
            cache: true,
            success: function(data){
                $('.form-control option').remove();
                $('#select1').append(data.select1);
                $('#select2').append(data.select2);
                $('#select3').append(data.select3);
            }
        });
        
        //mise à jour de la liste
        $.ajax({
            type: "POST",
            url: "admin.php?control="+categorie+"&action=afficherListe",
            data: choix,
            dataType: 'html',
            cache: true,
            success: function(data){
                $('#liste div').remove();
                $('#liste').html(data);
                
            }
        });
    });
    

    /***********************
     *      Fiche vin      *
     ***********************/ 

    //Ajout d'une nouvelle ligne conditionnement fiches addvin/addpremium
    
    $('#conditionnements tbody tr').first().attr('class', 'clone');
    $('#conditionnements tbody tr').not('#btnajout,.clone').append('<img class="supp" src="images/supp.jpe" alt="Supprimer">');

    $('#ajoutligne').on('click', function(){
       var newtr = $('.clone').clone().insertBefore($('#ajoutligne').parent().parent()).attr('class', '');
       newtr.append('<img class="supp" src="images/supp.jpe" alt="Supprimer">');
       newtr.find('select[name*=vol]').attr('name', 'vol75');
       newtr.find('select[name*=cond]').attr('name', 'cond75');
       newtr.find('input[name*=ref]').attr('name', 'ref75').val("");
       newtr.find('input[name*=prix]').attr('name', 'prix75').val("");
    });
    
    //Click suppression ligne conditionnement
    $('#conditionnements').on('click','.supp',function(){
        $(this).parent().remove();
    });
    
    //Ajout id du conditionnement au name du select
    $('#conditionnements').on('change','select[name*=vol]', function(){
       var idcond = $(this).val(); 
       $(this).attr('name', 'vol'+idcond);
       $(this).parent().parent().find('select[name*=cond]').attr('name', 'cond'+idcond);
       $(this).parent().parent().find('input[name*=ref]').attr('name', 'ref'+idcond);
       $(this).parent().parent().find('input[name*=prix]').attr('name', 'prix'+idcond);
    });
    

    // Aperçu de l'image avant upload 
    if($('.pop img').attr('src') === ""){
        $('.pop img').hide();
    }
    $('#photo, #photodom, #img').on('change', function(){
        var id = $(this).attr('id');
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#apercu'+id).attr('src', e.target.result);
                $('#apercu'+id).show();
                if(id != "img"){
                    $('#myModal img').attr('src', e.target.result);
                }else{
                    $('#imgmodal img').attr('src', e.target.result);
                    console.log($(this));
                }
            }
            reader.readAsDataURL(this.files[0]);
        }          
    });
    
    // Confirmation de suppression
    $('.delete').on('click', function(event){
        if(!confirm('Voulez-vous vraiment supprimer cette fiche ?')){
            event.preventDefault();
        }
    });
    
     
    // Popup ajout
    String.prototype.ucfirst = function()
        {
            return this.charAt(0).toUpperCase() + this.substr(1);
        }
    
    var popupform = 'button.popbtn ';
    $(popupform).click(function(){
        var id = $(this).parent().attr('id'),
        nomcat = id.substr(4),
        categorie = nomcat.ucfirst(),
        form = $('#'+id)[0],
        control = $(this).prev().attr('class').substr(13);
        
        if($('#'+id+' input').val() !== ""){
            $.ajax({
                type: "POST",
                url: "admin.php?control="+control+"&action=add"+categorie,
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data){
                    $('#'+nomcat).append('<option selected="">'+data.nom+'</option>');
                }
            });
        }
    });
    
    //Datepicker fiche offre
    $('.datepicker').datepicker();
    
    $('input[name*="ref"], input[name*="prix"]').on('focus', function(){
        var id = $(this).attr('id'),
        ref;
        if(id.indexOf("ref") === 0){
            ref = id.substr(3);
        }else{
            ref = id.substr(4);
        }
        $('input[name="vol'+ref+'"]').css('background-color', 'rgb(42, 171, 210)');
    });
    $('input[name*="ref"], input[name*="prix"]').on('blur', function(){
        var id = $(this).attr('id'),
        ref;
        if(id.indexOf("ref") === 0){
            ref = id.substr(3);
        }else{
            ref = id.substr(4);
        }
        $('input[name="vol'+ref+'"]').css('background-color', 'rgb(238, 238, 238)');
    });
    
    

    
    

});
