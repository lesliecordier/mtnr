/*******************************
 *         FONCTIONS           *
 *******************************/


function afficheLigne(classeLigne, objet) {
    var array = $(classeLigne);
    $.each(array, function () {
        //On passe la propriété 'visible' de chaque ligne à true
        var id = $(this).attr('id').split("-"),
                classes = $(this).attr('class').split(" ");
        objet[id[1]].visible = false;
        //Si la table est affichée, et 
        if ($("#table-" + classes[0]).css('display') !== "none") {
            objet[id[1]].visible = true;
            $("#idVin-" + id[1]).show();
        }
    });
}

function cacheLigne(classeLigne, objet) {
    $(classeLigne).hide();
    var array = $(classeLigne);
    $.each(array, function () {
        //On passe la propriété 'visible' de chaque ligne à false
        var id = $(this).attr('id').split("-");
        objet[id[1]].visible = false;
    });
}

function initCheckboxesVin(objet) {
    $.each(objet, function (id, value) {
        if (id > 0) {
            value.visible = true;
            $(".type-" + value.couleurmin).show();
            $("." + value.domainemin).show();
            $('#' + value.regionmin).prop('checked', true).prop('disabled', false).checkboxradio('refresh');
            $("#type-" + value.couleurmin).prop('checked', true).prop('disabled', false).checkboxradio('refresh');
            $("#" + value.domainemin).prop('checked', true).prop('disabled', false).checkboxradio('refresh');
            $("#" + value.domainemin).parent().show();
        }
    });
}

String.prototype.sansAccent = function () {
    var accent = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g, // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g, // C, c
    ];
    var noaccent = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];

    var str = this;
    for (var i = 0; i < accent.length; i++) {
        str = str.replace(accent[i], noaccent[i]);
    }

    return str;
}

/*----------------------*
 * Checkbox des régions *
 *----------------------*/
function createCheckboxRegion(vins) {
    console.log("region");
    var fieldsetR = '<fieldset class="tailleFieldset" data-role="controlgroup">',
            pR = '<p class="titreCheckbox" id="titreRegion">Régions</p>',
            cocheToutR = '<a href="#" id="chkRegion" class="chk">Tout cocher / décocher</a>',
            divR = '<div>',
            inputR = '',
            region,
            nbRegion = 0,
            doubleVin = [];
    $.each(vins, function (idvin, vin) {
        if (idvin > 0) {
            if (!doubleVin[vin['region']]) {
                doubleVin[vin['region']] = true;
                region = vin['region'].split(" (");
                region = region[0].toLowerCase().replace(/ /g, "-").sansAccent();
                inputR += '<input type="checkbox" name="' + region + '" id="' + region + '">';
                inputR += '<label for="' + region + '">' + vin['region'] + '</label>';

                /* On crée une table pour chaque région */
                var table = '<table data-role="table" class="tablesorter ui-responsive ui-table ui-table-reflow fondGris" id="table-' + region + '">';
                table += '<thead><tr><th class="code"></th><th class="titreTable padding-left">' + vin['region'] + '</th><th class="tarif">';
                table += '</th><th class="pdf"></th></tr></thead><tbody></tbody></table>';
                $('#listeVins').append(table);

                nbRegion++;
            }
        }
    });

    /* Ajout du fieldset créé dans la popup checkbox */
    fieldsetR += pR + cocheToutR + divR + inputR + '</div></fieldset>';
    $('#popup-checkbox-region').append(fieldsetR).trigger('create');
}

/*-----------------------*
 * Checkbox des domaines *
 *-----------------------*/

function createCheckboxDomaine(vins) {
    console.log("domaine");
    var fieldsetD = '<fieldset class="tailleFieldset" data-role="controlgroup">',
            pD = '<p class="titreCheckbox" id="titreDomaine">Domaines</p>',
            divD = '<div>',
            cocheToutD = '<a href="#" id="chkDomaine" class="chk">Tout cocher / décocher</a>',
            inputD = "",
            domaine,
            nbDomaine = 0,
            doubleDomaine = [];
    $.each(vins, function (idvin, vin) {
        if (idvin > 0) {
            if (!doubleDomaine[vin['domaine']]) {
                doubleDomaine[vin['domaine']] = true;
                domaine = vin['domaine'].split(" (");
                domaine = domaine[0].toLowerCase().replace(/ /g, "-").replace(/"/g, '').replace(/'/g, "-").sansAccent();
                // On crée une checkbox pour chaque domaine
                inputD += '<input type="checkbox" name="' + domaine + '" id="' + domaine + '">';
                inputD += '<label for="' + domaine + '">' + vin['domaine'] + '</label>';

                nbDomaine++;
            }
        }
    });

    /* Ajout du fieldset créé dans la popup checkbox */
    fieldsetD += pD + cocheToutD + divD + inputD + '</div></fieldset>';
    $('#popup-checkbox-domaine').append(fieldsetD);
    $('#popup-checkbox-domaine').trigger('create');
}

/*------------------------*
 * Checkbox des couleurs  *
 *------------------------*/

function createCheckboxCouleur(vins) {
    console.log("couleur");
    var fieldset = '<fieldset class="tailleFieldset" data-role="controlgroup">',
            p = '<p class="titreCheckbox" id="titreCouleur">Couleurs</p>',
            div = '<div>',
            inputs = "",
            couleur,
            nbCouleur = 0,
            doubleCouleur = [];

    $.each(vins, function (idvin, vin) {
        if (idvin > 0) {
            if (!doubleCouleur[vin['couleur']]) {
                doubleCouleur[vin['couleur']] = true;
                couleur = vin['couleur'].toLowerCase().sansAccent();
                /* On crée une checkbox pour chaque couleur */
                inputs += '<input type="checkbox" name="type-' + couleur + '" id="type-' + couleur + '" checked="checked">';
                inputs += '<label for="type-' + couleur + '">' + vin['couleur'] + '</label>';
                nbCouleur++;
            }
        }
    });

    /* Ajout du fieldset créé dans la popup checkbox */
    fieldset += p + div + inputs + '</div></fieldset>';
    $('#popup-checkbox-couleur').append(fieldset);
    $('#popup-checkbox-couleur').trigger('create');

    /* On crée une table pour les résultats de la recherche */
    var tableRes = '<table data-role="table" class="tablesorter ui-responsive ui-table ui-table-reflow fondGris" id="resultats_recherche">';
    tableRes += '<thead><tr><th class="code"></th><th class="titreTable padding-left">Résultats de la recherche</th>';
    tableRes += '<th class="tarif"></th><th class="pdf"></th></tr></thead><tbody></tbody></table>';
    $('#listeVins').append(tableRes);
}

/*-------------------------------------------------*
 * Ajout des lignes vins dans la table par région  *
 *-------------------------------------------------*/
function addLignesVins(vins) {
    console.log("add");
    var ligne = [],
            domaine,
            region,
            couleur,
            idTable;
    $.each(vins, function (idvin, vin) {
        if (idvin > 0) {
            //Création de la ligne vin
            domaine = vin['domaine'].split(" (");
            domaine = domaine[0].toLowerCase().replace(/ /g, "-").replace(/"/g, '').replace(/'/g, "-").sansAccent();
            region = vin['region'].split(" (");
            region = region[0].toLowerCase().replace(/ /g, "-").sansAccent();
            couleur = vin['couleur'].toLowerCase().sansAccent();

            ligne[vin['idVin']] += '<tr id=idVin-' + vin['idVin'] + ' class="' + region + ' ' + domaine + ' type-' + couleur + ' ligneListe">';
            ligne[vin['idVin']] += '<td class="code">' + vin['prixRef1']['ref'] + '</td><td class="vin"><a href="#' + vin['idVin'] + '" data-rel="popup" data-transition="pop" data-position-to="window">' + vin['domaine'] + " - " + vin['nom'] + '</a></td>';
            ligne[vin['idVin']] += '<td class="tarif">' + vin['prixRef1']['prix'] + ' €</td>';
            ligne[vin['idVin']] += '<td class="pdf">';
            if (vin['pdf'].indexOf('#') === -1) {
                ligne[vin['idVin']] += '<a href="' + vin['pdf'] + '" target="_blank"><img src="icones/doc.png" alt="document" style="width: 35px; height: 35px"></a>';
            }
            ;
            ligne[vin['idVin']] += '</td></tr>';
            $("#" + region).prop('checked', true).prop('disabled', false).checkboxradio('refresh');
            $("#type-" + couleur).prop('checked', true).prop('disabled', false).checkboxradio('refresh');
            $("#" + domaine).prop('checked', true).prop('disabled', false).checkboxradio('refresh');
            //Ajout dans la table
            idTable = "#table-" + region;
            $(idTable + " tbody").append(ligne[vin['idVin']]);
            $('#resultats_recherche tbody').append(ligne[vin['idVin']]);


            // Ajout des critères de tri renommés pour être utilisés dans les classes et id
            vins[idvin]['domainemin'] = domaine;
            vins[idvin]['regionmin'] = region;
            vins[idvin]['couleurmin'] = couleur;

        }


    });
}


/*----------------------------*
 *          Recherche         *
 *----------------------------*/

function search(vins) {
    $(document).on('keyup', '#search', function () {
        var recherche = $("#search").val().toLowerCase();
        if (recherche.length >= 1) {
            $('#listeVins table').hide();
            $('#resultats_recherche').show();
            $('#resultats_recherche tr[id*=idVin]').hide();
            $("#listevins :checkbox").prop('disabled', true).checkboxradio('refresh');
            $("#listevins fieldset a").hide();

            $.each(vins, function (index, value) {
                $.each(value, function (key, val) {
                    if ($.type(val) === 'number') {
                        val = val.toString();
                    }
                    if ($.type(val) === 'string') {
                        if (val.toLowerCase().indexOf(recherche) >= 0 || val.indexOf(recherche) >= 0) {
                            $('#resultats_recherche tr[id=idVin-' + index + ']').show();
                        }
                    } else if ($.type(val) === 'object') {
                        $.each(val, function (key2, val2) {
                            if ($.type(val2) === 'number') {
                                val2 = val2.toString();
                            }
                            if ($.type(val2) === 'string') {
                                if (val2.toLowerCase().indexOf(recherche) >= 0 || val2.indexOf(recherche) >= 0) {
                                    $('#resultats_recherche tr[id=idVin-' + index + ']').show();
                                }
                            }
                        });
                    }
                });
            });
        } else if (recherche === "") {
            $('#listeVins table').show();
            $('#resultats_recherche').hide();
            $("#listevins fieldset a").show();
            initCheckboxesVin(vins);
        }
    });
    $(document).on('click', '#barreRecherche a.ui-icon-delete', function () {
        $('#listeVins table').show();
        $('#resultats_recherche').hide();
        $("#listevins fieldset a").show();
        initCheckboxesVin(vins);
    });
}


/*----------------------------------------*
 * Affiche / cache ligne selon catégories *
 *----------------------------------------*/

function showHideLines(vins) {
    /* A chaque click sur l'image checkbox, on remet à jour les coches suivant les lignes visibles */
    $('#listevins .imgCheckbox').on('click', function () {
        $('#popup-checkbox-couleur .ui-checkbox input').prop('checked', false).checkboxradio('refresh');
        $("#popup-checkbox-domaine .ui-checkbox").hide();
        $.each(vins, function (idvin, value) {
            if (idvin > 0 && value.visible === true) {
                $("#" + value.regionmin).prop('checked', true).checkboxradio('refresh');
                $("#type-" + value.couleurmin).prop('checked', true).checkboxradio('refresh');
                $("#" + value.domainemin).prop('checked', true).checkboxradio('refresh');
                $("#" + value.domainemin).parent().show();
            }
        });
    });


    /* Affiche/cache la ligne selon catégorie cochée/décochée */
    $('#listevins :checkbox').on('change', function () {
        var categorie = $(this).parent().parent().parent().children('p').attr('id');//id = titreCouleur / titreRegion / titreDomaine
        categorie = categorie.substr(5);
        var id = $(this).attr('id'),
                classeLigne = $("." + id),
                idTable = "#table-" + id; //idTable = "table-"+region
        /* Si Region, on affiche/cache la table et la ligne et et valorise la propriété 'visible' */
        if (categorie === 'Region') {

            /* Affiche/cache la table et désactive checkboxes */
            if (this.checked) {
                //On affiche les lignes et la table région
                $(idTable).show();
                afficheLigne(classeLigne, vins);
            } else {
                //On cache les lignes et la table région
                $(idTable).hide();
                cacheLigne(classeLigne, vins);
            }
            /* Si Couleur ou Domaine on affiche/cache la ligne et valorise la propriété 'visible' */
        } else {
            if (this.checked) {
                afficheLigne(classeLigne, vins);
            } else {
                cacheLigne(classeLigne, vins);
            }
        }

    });
}

/*---------------------*
 * Fiches vins/domaines *
 *----------------------*/

function createPopup(vins) {
    $("tr[id*='idVin'] a").on('click', function () {

        var nom = $(this).text().split(" - "),
                nomdomaine = nom[0],
                nomvin = nom[1],
                href = $(this).attr('href').substr(1),
                millesime = vins[href]['millesime'],
                pdf = vins[href]['pdf'],
                prixTTC,
                table = '<table class="table-prix fondGris" id="tableprix"><thead><tr>';
        table += '<th>Code(s)</th><th>Condt Ventes</th><th>Volume(s)</th><th>Prix Vente H.T.</th><th>Prix Vente T.T.C.</th>';
        table += '</tr></thead><tbody>';
        //tableau des prix pour chaque conditionnement
        $.each(vins[href], function (cle, objet) {

            if (cle.indexOf('prixRef') >= 0) {
                if (objet["ref"] !== '') {
                    prixTTC = ((parseFloat(objet['prix'])) * 1.2).toFixed(3);
                    table += '<tr id="' + cle + '"><td>' + objet['ref'] + '</td><td>' + objet['conditionnement'] + '</td><td>' + objet['volume'] + '</td><td>' + objet['prix'] + ' €</td><td>' + prixTTC + ' €</td></tr>';
                }
            }
        });
        table += '</tbody></table>';

        $('.popupFicheVin').attr('id', href);
        $('.ficheVin .titreCheckbox').html(nomvin + "<br/>" + nomdomaine);
        $('.ficheVin .table-prix').html(table);
        if (millesime !== "") {
            $('.ficheVin .millesime').html("Millésime : <b>" + millesime + "</b>").css('display', 'block');
        } else {
            $('.ficheVin .millesime').css('display', 'none');
        }
        if (pdf !== '#') {
            $('.lienPdf').attr('href', pdf).css('display', 'block');
        } else {
            $('.lienPdf').css('display', 'none');
        }


    });
}

// Lien tout cocher/décocher checkboxes     
function checkAll(vins) {
    $('a.chk').on('click', function () {
        var categorie = $(this).attr('id').substr(3),
                objet = vins;
        // On coche/décoche tous les checkboxes
        if ($('#popup-checkbox-' + categorie.toLowerCase() + ' input').prop('checked')) {
            $('#popup-checkbox-' + categorie.toLowerCase() + ' input').prop('checked', false).checkboxradio('refresh');
        } else {
            $('#popup-checkbox-' + categorie.toLowerCase() + ' input').prop('checked', true).checkboxradio('refresh');
        }
        // On affiche/cache toutes les lignes 
        $('#popup-checkbox-' + categorie.toLowerCase() + ' input').each(function () {//on cherche dans tous les inputs du checkbox
            var id = $(this).attr('id'),
                    classeLigne = "." + id,
                    idTable = "#table-" + id;
            if (this.checked) {
                if (categorie === "Region") {
                    $(idTable).show();
                }
                afficheLigne(classeLigne, objet);
            } else {
                cacheLigne(classeLigne, objet);
                if (categorie === "Region") {
                    $(idTable).hide();
                }
            }
        });
    });
}





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