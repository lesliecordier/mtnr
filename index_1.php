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
       'photo' => $unVin['photo'],
       'descriptif' =>$unVin['descriptif'],
       'motsCles' => $unVin['motsCles'],
       'annee' => $unVin['annee']
    );
} 

$lesCondt = $pdo->getConditionnementVin();
foreach ($lesCondt as $unCondt){
    $vin[$unCondt['idVin']]['prix'.$unCondt['idConditionnement']] = array(
        'conditionnement' => $unCondt['conditionnement'],
        'volume' => $unCondt['volume'],
        'prix' => $unCondt['prixHT'],
        'prixTTC' => $unCondt['prixTTC'],
        'ref' => $unCondt['ref']
    );
}

$infosDomaines = $pdo->getInfosFicheDomaine();
foreach ($infosDomaines as $unDomaine){   
    $ficheDomaine[$unDomaine['idDomaine']] = array(
        'presentation' => $unDomaine['presentation'],
        'photoDomaine' => $unDomaine['photo']
    );
} 

/**********************
 *      BIERES        *
 **********************/


$lesBieres = $pdo->getBieres();
foreach ($lesBieres as $uneBiere){
    $biere[$uneBiere['idBiere']] = array(
        'nom' => $uneBiere['nom'],
        'contenance' => $uneBiere['contenance'],
        'taux' => $uneBiere['taux'],
        'prix' => $uneBiere['prix'],
        'prixTTC' => $uneBiere['prixTTC'],
        'photo' => $uneBiere['photo'],
        'description' => $uneBiere['description'],
        'pays' => $uneBiere['pays'],
        'idPays' => $uneBiere['idPays'],
        'couleur' => $uneBiere['couleur'],
        'idCouleur' => $uneBiere['idCouleur'],
        'idPresentation' => $uneBiere['idPresentation']
        
    );
}

$infosPresentation = $pdo->getInfosFichePresentation();
foreach ($infosPresentation as $unePresentation){   
    $fichePresentation[$unePresentation['idPresentation']] = array(
        'nom' => $unePresentation['nom'],
        'descriptif' => $unePresentation['descriptif'],
        'photo' => $unePresentation['photo']
    );
} 


/**********************
 *     PREMIUM        *
 **********************/


$lesPremiums = $pdo->getPremiums();
foreach ($lesPremiums as $unPremium){
    $premium[$unPremium['idPremium']] = array(
        'nom' => $unPremium['nom'],
        'taux' => $unPremium['taux'],
        'photo' => $unPremium['photo'],
        'description' => $unPremium['description'],
        'alcool' => $unPremium['alcool'],
        'idAlcool' => $unPremium['idAlcool'],
        'condRef' => $unPremium['condReference']
    );
}

$lesCondtPremium = $pdo->getConditionnementsPremiums();
foreach ($lesCondtPremium as $unCondt){
    $premium[$unCondt['idPremium']]['prix'.$unCondt['idCond']] = array(
        'volume' => $unCondt['volume'],
        'prix' => $unCondt['prixHT'],
        'prixTTC' => $unCondt['prixTTC'],
        'ref' => $unCondt['ref']
    );
    if($unCondt['idCond'] === $premium[$unCondt['idPremium']]['condRef']){
        $premium[$unCondt['idPremium']]['condRef'] = array(
            'volume' => $unCondt['volume'],
            'prix' => $unCondt['prixHT'],
            'prixTTC' => $unCondt['prixTTC'],
            'ref' => $unCondt['ref']
        );    
    }
}

/************************
 *   OFFRES SPECIALES   *
 ************************/


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


/*******************************
 *  DONNEES POUR REQUETE AJAX  *
 *******************************/


$donnees = array(
    'vins' => $vin,
    'ficheDomaine' => $ficheDomaine,
    'bieres' => $biere,
    'fichePresentation' => $fichePresentation,
    'premiums' => $premium,
    'offres' => $offre
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

