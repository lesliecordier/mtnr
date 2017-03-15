<?php
$action = $_GET['action'];
switch($action){
    
    case 'sommaire':{     
        include('vues/admin/v_sommaireVins.php');
        break;
    }
    
    case 'gestion':{     
        $regions = $pdo->getRegions();
        $couleurs = $pdo->getTypes();
        $domaines = $pdo->getDomaines();
        $vins = $pdo->getListeVins();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionVins.php');
        break;
    }
    
    case 'select':{   
        //au changement d'un select, on met à jour les autres selects selon les vins disponibles
        $nb = 0;
        $where = "WHERE ";
        $select['region'] = "<option></option>";
        $select['type'] = "<option></option>";
        $select['domaine'] = "<option></option>";   
        $double = array( 'region' => array(), 'type' => array(), 'domaine' => array());
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
        $vinsTries = $pdo->getVinsTries($where);
        foreach($vinsTries as $unVin){
            if(!isset($double['region'][$unVin['region']])){
                $select['region'] .= '<option>'.$unVin['region'].'</option>';
                $double['region'][$unVin['region']] = true;
            }
            if(!isset($double['type'][$unVin['couleur']])){
                $select['type'] .= '<option>'.$unVin['couleur'].'</option>';
                $double['type'][$unVin['couleur']] = true;
            }
            if(!isset($double['domaine'][$unVin['domaine']])){
                $select['domaine'] .= '<option>'.$unVin['domaine'].'</option>';
                $double['domaine'][$unVin['domaine']] = true;
            }
        }
        $retour = array(
            'select1' => $select['region'],
            'select2' => $select['type'],
            'select3' => $select['domaine'] 
        );
        echo json_encode($retour);
        break;
    }
    
    case 'afficherListe':{ 
        //mise à jour de la liste des vins selon select
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
        $vinsTries = $pdo->getVinsTries($where);
        $liste = "";
        $double = [];
        foreach($vinsTries as $titre){
            if (!isset($double[$titre['idRegion']])){
                $double[$titre['idRegion']] = true;
                $liste .= '<div id="region'.$titre['idRegion'].'" class="tablesorter panel panel-default">';
                $liste .= '<div class="panel-heading">'.$titre['region'].'</div>';
                $liste .= '<ul class="list-group">';
                foreach($vinsTries as $unVin){
                    if($unVin['idRegion'] == $titre['idRegion']){
                        $liste .= '<li id="vin'.$unVin['idVin'].'" class="list-group-item"><a href="admin.php?control=vins&action=ficheUpdate&id='.$unVin['idVin'].'">'.$unVin['nom'].'</a></li>';
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
        $domaines = $pdo->getDomaines();
        $types = $pdo->getTypes();
        $regions = $pdo->getRegions();
        include("vues/admin/v_addVin.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            if($key != 'domaine' || $key != 'type' || $key != 'region'){
                $$key = addslashes(trim($value));
            }else{
                $$key = trim($value);
            }
            if(empty($value)){
                $$key = "";
            }
        }
        $iddom = $pdo->getId('domaine', $domaine);
        $idtype = $pdo->getId('type', $type);
        $idreg = $pdo->getId('region', $region);
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'vins/');
        }else{
            $photo = "images/vins/defaut.jpg";
        }
        $etat1 = $pdo->insertVin($nom,$iddom,$idreg,$idtype,$photo,$publie, $descriptif, $motsCles, $annee);
        $idVin = $pdo->getId('vin', $nom);
        if (isset($ref27)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol27, $ref27, $prix27, $cond27);}
        if (isset($ref50)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol50, $ref50, $prix50, $cond50);}
        if (isset($ref75)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol75, $ref75, $prix75, $cond75);}
        if (isset($ref150)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol150, $ref150, $prix150, $cond150);}
        if (isset($ref300)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol300, $ref300, $prix300, $cond300);}
        if (isset($ref600)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol600, $ref600, $prix600, $cond600);}
        if (isset($ref1200)) {$etat2 = $pdo->insertConditionnementVin($idVin, $vol1500, $ref1500, $prix1500, $cond1500);}
        if($etat1 != 0 && $etat2 != 0){
            $message = 'Le vin <b>'.stripslashes($nom).'</b> a bien été ajouté.';
            $class = 'text-success bg-success';
        }else{
            if($etat2 == 0){
                $pdo->deleteVin($idVin);
            }
            $message = 'Une erreur est survenue, le vin <b>'.stripslashes($nom).'</b> n\'a pas été ajouté. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $regions = $pdo->getRegions();
        $couleurs = $pdo->getTypes();
        $domaines = $pdo->getDomaines();
        $vins = $pdo->getListeVins();
        include('vues/admin/v_gestionVins.php');
        break;
    }
    
    case 'addDomaine':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $photopopup = upload('photopopup', 'domaines/');
        $pdo->insertDomaine($nomdom,$photopopup,$presentation);
        $nomdom = htmlspecialchars(stripslashes($nomdom), ENT_QUOTES);
        $iddomainepopup = $pdo->getId('domaine', $nomdom);
        $retour = array(
            'id' => $iddomainepopup,
            'nom' => $nomdom
        );
        echo json_encode($retour);
        break;
    }
    
    case 'addRegion':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $pdo->insertRegion($nomregion);
        $id = $pdo->getId('region',$nomregion);
        $retour = array(
            'id' => $id,
            'nom' => $nomregion
        );
        echo json_encode($retour);
        break;
    }
    
    case 'ficheUpdate':{
        $idvin = $_GET['id'];
        $vin = $pdo->getVin($idvin);
        $conditionnement = $pdo->getConditionnement($idvin);
        $domaines = $pdo->getDomaines();
        $types = $pdo->getTypes();
        $regions = $pdo->getRegions();
        $message ="";
        $class = "";
        include("vues/admin/v_updateVin.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            if($key != 'domaine' || $key != 'type' || $key != 'region'){
                $$key = addslashes(trim($value));
            }else{
                $$key = trim($value);
            }
            if(empty($value)){
                $$key = "";
            }
        }
        $iddom = $pdo->getId('domaine', $domaine);
        $idtype = $pdo->getId('type', $type);
        $idreg = $pdo->getId('region', $region);
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'vins/');
        }else{
            $photo = $_POST['photoExistante'];
        }
        $etat1 = $pdo->updateVin($idVin,$nom,$iddom,$idreg,$idtype,$photo,$publie, $descriptif, $motsCles, $annee);
        $pdo->deleteConditionnementVin($idVin);
        if (isset($ref27)) {$pdo->insertConditionnementVin($idVin, $vol27, $ref27, $prix27, $cond27);}
        if (isset($ref50)) {$pdo->insertConditionnementVin($idVin, $vol50, $ref50, $prix50, $cond50);}
        if (isset($ref75)) {$pdo->insertConditionnementVin($idVin, $vol75, $ref75, $prix75, $cond75);}
        if (isset($ref150)) {$pdo->insertConditionnementVin($idVin, $vol150, $ref150, $prix150, $cond150);}
        if (isset($ref300)) {$pdo->insertConditionnementVin($idVin, $vol300, $ref300, $prix300, $cond300);}
        if (isset($ref600)) {$pdo->insertConditionnementVin($idVin, $vol600, $ref600, $prix600, $cond600);}
        if (isset($ref1200)) {$pdo->insertConditionnementVin($idVin, $vol1500, $ref1500, $prix1500, $cond1500);}
        if($etat1 != 0){
            $message = 'La mise à jour du vin <b>'.stripslashes($nom).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le vin <b>'.stripslashes($nom).'</b> n\'a pas été mis à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $regions = $pdo->getRegions();
        $couleurs = $pdo->getTypes();
        $domaines = $pdo->getDomaines();
        $vins = $pdo->getListeVins();
        include('vues/admin/v_gestionVins.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $photo = $pdo->getPhoto($idvin, 'vin');
        $etat = $pdo->deleteVin($idvin);
        //Retour au sommaire
        $regions = $pdo->getRegions();
        $couleurs = $pdo->getTypes();
        $domaines = $pdo->getDomaines();
        $vins = $pdo->getListeVins();
        if($etat != 0){
            if($photo != "images/vins/defaut.jpg"){
                unlink($photo);
            }
            $message = 'Le vin <b>'.stripslashes($nom).'</b> a bien été supprimé.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le vin <b>'.stripslashes($nom).'</b> n\'a pas été supprimé. Veuillez renouveler la suppression.';
            $class = 'text-danger bg-danger';
        }
        include('vues/admin/v_gestionVins.php');
        break;
    }
    
}
