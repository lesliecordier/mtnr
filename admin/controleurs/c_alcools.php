<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $alcools = $pdo->getAlcools();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionAlcools.php');
        break;
    }
    
    case 'ficheAdd':{
        include("vues/admin/v_addAlcool.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $etat = $pdo->insertAlcool($nomalcool);
        if($etat != 0){
            $message = 'L\'alcool <b>'.stripslashes($nomalcool).'</b> a bien été ajouté.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, l\'alcool <b>'.stripslashes($nomalcool).'</b> n\'a pas été ajouté. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $alcools = $pdo->getAlcools();
        include('vues/admin/v_gestionAlcools.php');
        break;
    }
    
    case 'ficheUpdate':{
        $idalcool = $_GET['id'];
        $alcool = $pdo->getAlcool($idalcool);
        include("vues/admin/v_updateAlcool.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $etat = $pdo->updateAlcool($idalcool,$nomalcool);
        if($etat != 0){
            $message = 'La mise à jour de l\'alcool <b>'.stripslashes($nomalcool).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, l\'alcool <b>'.stripslashes($nomalcool).'</b> n\'a pas été mis à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $alcools = $pdo->getAlcools();
        include('vues/admin/v_gestionAlcools.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $premiumsAssocies = $pdo->deleteAlcool($idalcool);
        if($premiumsAssocies){
            $alcool = $pdo->getAlcool($idalcool);
            include("vues/admin/v_updateAlcool.php");
        }else{
            $message = 'L\'alcool <b>'.stripslashes($nomalcool).'</b> a bien été supprimé.';
            $class = 'text-success bg-success';
            $alcools = $pdo->getAlcools();
            include('vues/admin/v_gestionAlcools.php');
        } 
        break;
    }
    
}
