<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $regions = $pdo->getRegions();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionRegions.php');
        break;
    }
    
    case 'ficheAdd':{
        include("vues/admin/v_addRegion.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $etat = $pdo->insertRegion($nomreg);
        if($etat != 0){
            $message = 'La région <b>'.stripslashes($nomreg).'</b> a bien été ajoutée.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la région <b>'.stripslashes($nomreg).'</b> n\'a pas été ajoutée. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $regions = $pdo->getRegions();
        include('vues/admin/v_gestionRegions.php');
        break;
    }
    
    case 'ficheUpdate':{
        $idreg = $_GET['id'];
        $reg = $pdo->getRegion($idreg);
        include("vues/admin/v_updateRegion.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $etat = $pdo->updateRegion($idregion,$nomreg);
        if($etat != 0){
            $message = 'La mise à jour de la région <b>'.stripslashes($nomreg).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la région <b>'.stripslashes($nomreg).'</b> n\'a pas été mise à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $regions = $pdo->getRegions();
        include('vues/admin/v_gestionRegions.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $domAssocies = $pdo->deleteRegion($idreg);
        if($domAssocies){
            $reg = $pdo->getRegion($idreg);
            include("vues/admin/v_updateRegion.php");
        }else{
            $message = 'La région <b>'.stripslashes($nomreg).'</b> a bien été supprimée.';
            $class = 'text-success bg-success';
            $regions = $pdo->getRegions();
            include('vues/admin/v_gestionRegions.php');
        } 
        break;
    }
    
}
