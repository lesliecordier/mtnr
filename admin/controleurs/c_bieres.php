<?php
$action = $_GET['action'];
switch($action){
    
    case 'sommaire':{     
        include('vues/admin/v_sommaireBieres.php');
        break;
    }
    
    case 'gestion':{     
        $pays = $pdo->getPays();
        $couleurs = $pdo->getCouleurs();
        $bieres = $pdo->getListeBieres();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionBieres.php');
        break;
    }
    
    case 'select':{   
        //au changement d'un select, on met à jour les autres selects selon les bieres disponibles
        $nb = 0;
        $where = "WHERE ";
        $select['pays'] = "<option></option>";
        $select['couleur'] = "<option></option>";
        $double = array( 'pays' => array(), 'couleur' => array());
        foreach($_POST as $key=>$value){
            if($value != ""){
                if($nb >= 1){
                    $where .=  ' AND ';
                }
                $where .= 'montaner_'.$key.'.nom = "'.$value.'"';
                $nb++;
                $select[$key] .="<option selected>".$value."</option>";
                $double[$key] = array($value => true);
            }
            
        }
        $bieresTriees = $pdo->getBieresTriees($where);
        foreach($bieresTriees as $uneBiere){
            if(!isset($double['pays'][$uneBiere['pays']])){
                $select['pays'] .= '<option>'.$uneBiere['pays'].'</option>';
                $double['pays'][$uneBiere['pays']] = true;
            }
            if(!isset($double['couleur'][$uneBiere['couleur']])){
                $select['couleur'] .= '<option>'.$uneBiere['couleur'].'</option>';
                $double['couleur'][$uneBiere['couleur']] = true;
            }
        }
        $retour = array(
            'select1' => $select['pays'],
            'select2' => $select['couleur']
        );
        echo json_encode($retour);
        break;
    }
    
    case 'afficherListe':{ 
        //mise à jour de la liste des bières selon select
        $nb = 0;
        $where = "WHERE ";
        foreach($_POST as $key=>$value){
            if($value != ""){
                if($nb >= 1){
                    $where .=  ' AND ';
                }
                $array[$key] = $value;
                $where .= 'montaner_'.$key.'.nom = "'.$value.'"';
                $nb++;
            }
        }
        $bieresTriees = $pdo->getBieresTriees($where);
        $liste = "";
        $double = [];
        foreach($bieresTriees as $titre){
            if (!isset($double[$titre['idPays']])){
                $double[$titre['idPays']] = true;
                $liste .= '<div id="pays'.$titre['idPays'].'" class="panel panel-default">';
                $liste .= '<div class="panel-heading">'.$titre['pays'].'</div>';
                $liste .= '<ul class="list-group">';
                foreach($bieresTriees as $uneBiere){
                    if($uneBiere['idPays'] == $titre['idPays']){
                        $liste .= '<li id="biere'.$uneBiere['idBiere'].'" class="list-group-item"><a href="admin.php?control=bieres&action=ficheUpdate&id='.$uneBiere['idBiere'].'">'.$uneBiere['nom'].'</a></li>';
                    }
                }
            $liste .= '</ul></div>';
            }
        }
        $liste .= '</div>'; 
        echo $liste;
        break;
    }
    
    case 'ficheAdd':{
        $pays = $pdo->getPays();
        $couleurs = $pdo->getCouleurs();
        $presentations = $pdo->getInfosFichePresentation();
        $bieres = $pdo->getListeBieres();
        include("vues/admin/v_addBiere.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            if($key != 'couleur' || $key != 'pays' || $key != 'presentation'){
                str_replace(",", ".", $value);
                $$key = addslashes(trim($value));
            }else{
                $$key = trim($value);
            }
        }
        $idcouleur = $pdo->getId('couleur', $couleur);
        $idpays = $pdo->getId('pays', $pays);
        $idpres = $pdo->getId('presentation', $presentation);
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'bieres/');
        }else{
            $photo = "images/bieres/non-dispo.jpg";
        }
        $etat = $pdo->insertBiere($ref, $nom, $volume, $taux, $prix, $photo, $descriptif, $publie, $idpays, $idcouleur, $idpres);
        if($etat != 0){
            $message = 'La bière <b>'.stripslashes($nom).'</b> a bien été ajoutée.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la bière <b>'.stripslashes($nom).'</b> n\'a pas été ajoutée. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $pays = $pdo->getPays();
        $couleurs = $pdo->getCouleurs();
        $bieres = $pdo->getListeBieres();
        include('vues/admin/v_gestionBieres.php');
        break;
    }
    
    case 'addPresentation':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $photopopup = upload('photopopup', 'presentations/');
        $pdo->insertPresentation($nompres, $presentation, $photopopup);
        $idprespopup = $pdo->getId('presentation', $nompres);
        $retour = array(
            'id' => $idprespopup,
            'nom' => $nompres
        );
        echo json_encode($retour);
        break;
    }
    
    case 'addPays':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $idpays = strtolower(substr($nompays, 0, 3));
        $pdo->insertPays($idpays,$nompays);
        $retour = array(
            'id' => $idpays,
            'nom' => $nompays
        );
        echo json_encode($retour);
        break;
    }
    
    case 'ficheUpdate':{
        $idbiere = $_GET['id'];
        $biere = $pdo->getBiere($idbiere);
        $pays = $pdo->getPays();
        $couleurs = $pdo->getCouleurs();
        $presentations = $pdo->getInfosFichePresentation();
        include("vues/admin/v_updateBiere.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            str_replace(",", ".", $value);
            $$key = addslashes(trim($value));
        }
        $idcouleur = $pdo->getId('couleur', $couleur);
        $idpays = $pdo->getId('pays', $pays);
        $idpres = $pdo->getId('presentation', $presentation);
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'bieres/');
        }else{
            $photo = $_POST['photoExistante'];
        }
        $etat = $pdo->updateBiere($idBiere, $nom, $volume, $taux, $prix, $photo, $descriptif, $publie, $idpays, $idcouleur, $idpres);
        if($etat != 0){
            $message = 'La mise à jour de la bière <b>'.stripslashes($nom).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la bière <b>'.stripslashes($nom).'</b> n\'a pas été mise à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $pays = $pdo->getPays();
        $couleurs = $pdo->getCouleurs();
        $bieres = $pdo->getListeBieres();
        include('vues/admin/v_gestionBieres.php');
        break;
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $photo = $pdo->getPhoto($idbiere, 'biere');
        $etat = $pdo->deleteBiere($idbiere);
        //Retour au sommaire
        $pays = $pdo->getPays();
        $couleurs = $pdo->getCouleurs();
        $bieres = $pdo->getListeBieres();
        if($etat != 0){
            if($photo != "images/bieres/non-dispo.jpg"){
                unlink($photo);
            }
            $message = 'La bière <b>'.stripslashes($nom).'</b> a bien été supprimée.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la bière <b>'.stripslashes($nom).'</b> n\'a pas été supprimée. Veuillez renouveler la suppression.';
            $class = 'text-danger bg-danger';
        }
        include('vues/admin/v_gestionBieres.php');
        break;
    }
    
}
