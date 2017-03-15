<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $pays = $pdo->getPays();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionPays.php');
        break;
    }
    
    case 'ficheAdd':{
        include("vues/admin/v_addPays.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $idpays = strtolower(substr($nompays, 0, 3));
        $etat = $pdo->insertPays($idpays,$nompays);
        if($etat != 0){
            $message = 'Le pays <b>'.stripslashes($nompays).'</b> a bien été ajouté.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue,  le pays <b>'.stripslashes($nompays).'</b> n\'a pas été ajouté. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $pays = $pdo->getPays();
        include('vues/admin/v_gestionPays.php');
        break;
    }
    
    case 'ficheUpdate':{
        $idpays = $_GET['id'];
        $pays = $pdo->getUnPays($idpays);
        include("vues/admin/v_updatePays.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $etat = $pdo->updatePays($idpays,$nompays);
        if($etat != 0){
            $message = 'La mise à jour du pays <b>'.stripslashes($nompays).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le pays <b>'.stripslashes($nompays).'</b> n\'a pas été mis à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $pays = $pdo->getPays();
        include('vues/admin/v_gestionPays.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $bieresAssociees = $pdo->deletePays($idpays);
        if($bieresAssociees){
            $pays = $pdo->getUnPays($idpays);
            include("vues/admin/v_updatePays.php");
        }else{
            $message = 'Le pays <b>'.stripslashes($nompays).'</b> a bien été supprimé.';
            $class = 'text-success bg-success';
            $pays = $pdo->getPays();
            include('vues/admin/v_gestionPays.php');
        } 
        break;
    }
    
}
