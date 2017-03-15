<?php
$action = $_GET['action'];
switch($action){
    
    case 'sommaire':{     
        include('vues/admin/v_sommairePremiums.php');
        break;
    }
    
    case 'gestion':{     
        $alcools = $pdo->getAlcools();
        $premiums = $pdo->getListePremiums();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionPremiums.php');
        break;
    }
    
    case 'afficherListe':{ 
        //mise à jour de la liste des premiums selon select
        $nb = 0;
        $where = "WHERE ";
        foreach($_POST as $key=>$value){
            if($value != ""){
                $array[$key] = $value;
                $where .= 'montaner_'.$key.'.nom = "'.$value.'"';
                $nb++;
            }
        }
        $premiumsTries = $pdo->getPremiumsTries($where);
        $liste = "";
        $double = [];
        foreach($premiumsTries as $titre){
            if (!isset($double[$titre['idAlcool']])){
                $double[$titre['idAlcool']] = true;
                $liste .= '<div id="premium'.$titre['idAlcool'].'" class="tablesorter panel panel-default">';
                $liste .= '<div class="panel-heading">'.$titre['alcool'].'</div>';
                $liste .= '<ul class="list-group">';
                foreach($premiumsTries as $unPremium){
                    if($unPremium['idAlcool'] == $titre['idAlcool']){
                        $liste .= '<li id="premium'.$unPremium['idPremium'].'" class="list-group-item"><a href="admin.php?control=premiums&action=ficheUpdate&id='.$unPremium['idPremium'].'">'.$unPremium['nom'].'</a></li>';
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
        $alcools = $pdo->getAlcools();
        $premiums = $pdo->getPremiums();
        $conditionnement = $pdo->getConditionnementPremiumGeneral();
        include("vues/admin/v_addPremium.php");
        break;
    }
    
    case 'add':{
        $etat2 = 1;
        foreach ($_POST as $key => $value){
            if($key != 'alcool'){
                str_replace(",", ".", $value);
                $$key = addslashes(trim($value));
            }else{
                $$key = trim($value);
            }
            if(empty($value)){
                $$key = "";
            }
        }
        $idalcool = $pdo->getId('alcool', $alcool);
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'premiums/');
        }else{
            $photo = "images/premiums/non-dispo.jpg";
        }
        $etat1 = $pdo->insertPremium($nom, $taux, $photo, $descriptif, $publie, $condRef, $idalcool);
        $idPremium = $pdo->getId('premium', $nom);
        if (isset($ref70)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol70, $ref70, $cond70, $prix70);}
        if (isset($ref11)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol11, $ref11, $cond11, $prix11);}
        if (isset($ref20)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol20, $ref20, $cond20, $prix20);}
        if (isset($ref50)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol50, $ref50, $cond50, $prix50);}
        if (isset($ref75)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol75, $ref75, $cond75, $prix75);}
        if (isset($ref150)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol150, $ref150, $cond150, $prix150);}
        if (isset($ref300)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol300, $ref300, $cond300, $prix300);}
        if (isset($ref600)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol600, $ref600, $cond600, $prix600);}
        if (isset($ref900)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol900, $ref900, $cond900, $prix900);}
        if (isset($ref1200)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol1200, $ref1200, $cond1200, $prix1200);}
        if (isset($ref1500)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol1500, $ref1500, $cond1500, $prix1500);}
        if($etat1 != 0 && $etat2 != 0){
            $message = 'Le premium <b>'.stripslashes($nom).'</b> a bien été ajouté.';
            $class = 'text-success bg-success';
        }else{
            if($etat2 == 0){
                $pdo->deletePremium($idpremium);
            }
            $message = 'Une erreur est survenue, le premium <b>'.stripslashes($nom).'</b> n\'a pas été ajouté. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $alcools = $pdo->getAlcools();
        $premiums = $pdo->getListePremiums();
        include('vues/admin/v_gestionPremiums.php');
        break;
    }
    
    case 'addAlcool':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $pdo->insertAlcool($nomalcool);
        $idalcoolpopup = $pdo->getId('alcool', $nomalcool);
        $retour = array(
            'id' => $idalcoolpopup,
            'nom' => $nomalcool
        );
        echo json_encode($retour);
        break;
    }
    
    case 'ficheUpdate':{
        $idpremium = $_GET['id'];
        $premium = $pdo->getPremium($idpremium);
        $conditionnement = $pdo->getConditionnementPremium($idpremium);
        $condRef = $pdo->getConditionnementRef($idpremium);
        $alcools = $pdo->getAlcools();
        $message ="";
        $class = "";
        include("vues/admin/v_updatePremium.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            if($key != 'alcool'){
                str_replace(",", ".", $value);
                $$key = addslashes(trim($value));
            }else{
                $$key = trim($value);
            }
            if(empty($value)){
                $$key = "";
            }
        }
        $idalcool = $pdo->getId('alcool', $alcool);
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'premiums/');
        }else{
            $photo = $_POST['photoExistante'];
        }
        $etat1 = $pdo->updatePremium($idPremium, $nom, $taux, $photo, $description, $publie, $condRef, $idalcool);
        $pdo->deleteConditionnementPremium($idPremium);
        if (isset($ref70)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol70, $ref70, $cond70, $prix70);}
        if (isset($ref11)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol11, $ref11, $cond11, $prix11);}
        if (isset($ref20)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol20, $ref20, $cond20, $prix20);}
        if (isset($ref50)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol50, $ref50, $cond50, $prix50);}
        if (isset($ref75)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol75, $ref75, $cond75, $prix75);}
        if (isset($ref150)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol150, $ref150, $cond150, $prix150);}
        if (isset($ref300)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol300, $ref300, $cond300, $prix300);}
        if (isset($ref600)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol600, $ref600, $cond600, $prix600);}
        if (isset($ref900)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol900, $ref900, $cond900, $prix900);}
        if (isset($ref1200)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol1200, $ref1200, $cond1200, $prix1200);}
        if (isset($ref1500)) {$etat2 = $pdo->insertConditionnementPremium($idPremium, $vol1500, $ref1500, $cond1500, $prix1500);}
        if($etat1 != 0 && $etat2 != 0){
            $message = 'La mise à jour du premium <b>'.stripslashes($nom).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le premium <b>'.stripslashes($nom).'</b> n\'a pas été mis à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $alcools = $pdo->getAlcools();
        $premiums = $pdo->getListePremiums();
        include('vues/admin/v_gestionPremiums.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $photo = $pdo->getPhoto($idpremium, 'premium');
        $etat = $pdo->deletePremium($idpremium);
        //Retour au sommaire
        $alcools = $pdo->getAlcools();
        $premiums = $pdo->getListePremiums();
        if($etat != 0){
            if($photo != "images/premiums/non-dispo.jpg"){
                unlink($photo);
            }
            $message = 'Le premium <b>'.stripslashes($nom).'</b> a bien été supprimé.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le premium <b>'.stripslashes($nom).'</b> n\'a pas été supprimé. Veuillez renouveler la suppression.';
            $class = 'text-danger bg-danger';
        }
        include('vues/admin/v_gestionPremiums.php');
        break;
    }
    
}
