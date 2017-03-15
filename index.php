<?php 
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    include('vues/carte/v_entete.php');
}
require ('modeles/class.pdo.php');
$pdo = PdoBdd::getPdoBdd();
/**********************
 *        VINS        *
 **********************/

$lesVins = $pdo->getVins();
foreach ($lesVins as $unVin){
   $vin[$unVin['idVin']] = array(
       'nom' => $unVin['nom'],
       'couleur' => $unVin['couleur'],
       'idCouleur' => $unVin['idCouleur'],
       'domaine' => $unVin['domaine'],
       'idDomaine' => $unVin['idDomaine'],
       'region' => $unVin['region'],
       'idRegion' => $unVin['idRegion'],
       'idVin' => $unVin['idVin'],
       'annee' => $unVin['annee']
    );
} 

$lesCondt = $pdo->getConditionnementVin();
foreach ($lesCondt as $unCondt){
    // On recupère le volume pour créer un tableau pour chaque vin en fonction des différents volumes disponibles $vin[idVin]['prix75']
    $cond = split(' ', $unCondt['volume']);
    if (empty($cond[1])){
        $cond = split(",",substr($unCondt['volume'], 0, -2))[0];
    }else{
        $cond = split(",",$cond[0])[0];
    }
    $vin[$unCondt['idVin']]['prix'.$cond] = array(
        'conditionnement' => $unCondt['conditionnement'],
        'volume' => $unCondt['volume'],
        'prix' => $unCondt['prixHT'],
        'prixTTC' => $unCondt['prixTTC'],
        'ref' => $unCondt['ref']
    );
}
// On définie le volume de référence qui sera affiché dans la ligne, soit le 75cl s'il existe, sinon le plus petit volume 
foreach ($vin as $unVin){
    if(empty($unVin['prix75'])){
        for($i = 1; $i <= 1500; $i++){
            if(!empty($unVin['prix'.$i])){
                $vin[$unVin['idVin']]['prixRef'] = $unVin['prix'.$i];
                break 1;
            }
        }
    } else {
        $vin[$unVin['idVin']]['prixRef'] = $unVin['prix75'];
    }
}

/************************
 *   OFFRES SPECIALES   *
 ************************/

/*
$lesOffres = $pdo->getOffres();
$mois = array(
    1 => 'Janvier',
    2 => 'Février',
    3 => 'Mars',
    4 => 'Avril',
    5 => 'Mai',
    6 => 'Juin',
    7 => 'Juillet',
    8 => 'Août',
    9 => 'Septembre',
    10 => 'Octobre',
    11 => 'Novembre',
    12 => 'Décembre'
);
foreach ($lesOffres as $uneOffre){
    $offre[$uneOffre['datedmy']] = array(
        'nom' => $uneOffre['nom'],
        'id' => $uneOffre['idOffre'],
        'img' => $uneOffre['img'],
        'miniature' => $uneOffre['photo'],
        'mois' => $mois[$uneOffre['mois']]
    );
}
*/

/*******************************
 *  DONNEES POUR REQUETE AJAX  *
 *******************************/


$donnees = array(
    'vins' => $vin/*,
    'offres' => $offre*/
);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    echo json_encode($donnees);
}


/*******************
 *  AFFICHAGE VUE  *
 *******************/

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    include('vues/carte/v_carte.php');
}

