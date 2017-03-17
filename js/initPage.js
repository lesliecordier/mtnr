$(document).on("pagecreate", ".ui-page", function () {

    /*********************************
     *  Swipe navigation entre pages  *
     **********************************/

    var
            $page = $(this),
            page = "#" + $page.attr("id"),
            prev = $page.jqmData("prev");

    if (prev) {
        $page.on("swiperight", function () {
            $.mobile.changePage(prev, {transition: "slide", reverse: true});
        });
    }

});

$(function () {

    $('#vins').on('click', function () {
        /* Redirection au click de l'accueil*/
        $.mobile.changePage("#listevins", {transition: "slide"});
    });




    /***********************************
     *  Chargement de la page offres  *
     ***********************************/

    $('#offres').on('click', function () {

        $('#liste_offres').html('');

        // Titre diviseurs listview dynamique
        $("#liste_offres").listview({
            autodividers: true,
            autodividersSelector: function (li) {
                var out = li.attr('date');
                return out;
            }
        });

        /*-----------------*
         * Liste des offres *
         *------------------*/

        var liste = [];
        $.each(offres, function (index, offre) {
            liste[index] = '<li id="idOffre-' + offre['id'] + '"  date="' + offre['mois'] + '"><a href="#popOffre' + index + '-' + offre['id'] + '" data-rel="popup"';
            liste[index] += 'data-transition="pop" data-position-to="window"><img src="' + offre['miniature'] + '" alt="' + offre['nom'] + '">';
            liste[index] += '<div>' + index + '</div><div><b>' + offre['nom'] + '</b></div></a></li>';
            $('#liste_offres').append(liste[index]);
        });
        $('#liste_offres').listview('refresh');


        /*--------------*
         * Fiches offres *
         *---------------*/

        $('#liste_offres>li>a').on('click', function () {
            $('.ficheOffre .titreCheckbox').html('');
            $('.ficheOffre .offre').attr('src', '');

            var nom = $(this).text().substr(10),
                    id = $(this).attr('href').substr(9, 10),
                    idpopup = $(this).attr('href').substr(9),
                    img = offres[id]['img'];


            $('.popupFicheOffre').attr('id', 'popOffre' + idpopup);
            $('.ficheOffre .titreCheckbox').html(nom);
            $('.ficheOffre .offre').attr('src', img).attr('alt', nom);
        });

        // Redirection au click à l'accueil
        $.mobile.changePage("#page_offres", {transition: "slide"});

    });


});