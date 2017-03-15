<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $presentations = $pdo->getPresentations();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionPresentations.php');
        break;
    }
    
    case 'ficheAdd':{
        include("vues/admin/v_addPresentation.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'presentations/');
        }else{
            $photo = "images/presentations/non-dispo.jpg";
        }
        $etat = $pdo->insertPresentation($nom, $descriptif, $photo);
        if($etat != 0){
            $message = 'La présentation <b>'.stripslashes($nom).'</b> a bien été ajoutée.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la présentation <b>'.stripslashes($nom).'</b> n\'a pas été ajoutée. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $presentations = $pdo->getPresentations();
        include('vues/admin/v_gestionPresentations.php');
        break;
    }
    
    case 'ficheUpdate':{
        $idpresentation = $_GET['id'];
        $presentation = $pdo->getPresentation($idpresentation);
        include("vues/admin/v_updatePresentation.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        if($_FILES['photo']['error'] != 4){
            $photo = upload('photo', 'presentations/');
        }else{
            $photo = $_POST['photoExistante'];
        }
        $etat = $pdo->updatePresentation($idpresentation,$nom, $descriptif, $photo);
        if($etat != 0){
            $message = 'La mise à jour de la fiche presentation <b>'.stripslashes($nom).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la fiche présentation <b>'.stripslashes($nom).'</b> n\'a pas été mise à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $presentations = $pdo->getPresentations();
        include('vues/admin/v_gestionPresentations.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $photo = $pdo->getPhoto($idpresentation, 'presentation');
        $bieresAssociees = $pdo->deletePresentation($idpresentation);
        if($bieresAssociees){
            $presentation = $pdo->getPresentation($idpresentation);
            include("vues/admin/v_updatePresentation.php");
        }else{
            if($photo != "images/presentations/non-dispo.jpg"){
                unlink($photo);
            }
            $message = 'La présentation <b>'.stripslashes($nom).'</b> a bien été supprimée.';
            $class = 'text-success bg-success';
            $presentations = $pdo->getPresentations();
            include('vues/admin/v_gestionPresentations.php');
        } 
        break;
    }
    
}
