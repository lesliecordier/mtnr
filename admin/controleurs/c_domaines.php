<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $domaines = $pdo->getDomaines();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionDomaines.php');
        break;
    }
    
    case 'ficheAdd':{
        include("vues/admin/v_addDomaine.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        if($_FILES['photodom']['error'] != 4){
            $photoD = upload('photodom', 'domaines/');
        }else{
            $photoD = "images/domaines/non-dispo.jpg";
        }
        $etat = $pdo->insertDomaine($nomdom,$photoD, $presentation);
        if($etat != 0){
            $message = 'Le domaine <b>'.stripslashes($nomdom).'</b> a bien été ajouté.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le domaine <b>'.stripslashes($nomdom).'</b> n\'a pas été ajouté. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $domaines = $pdo->getDomaines();
        include("vues/admin/v_gestionDomaines.php");
        break;
    }
    
    case 'ficheUpdate':{
        $iddom = $_GET['id'];
        $dom = $pdo->getDomaine($iddom);
        include("vues/admin/v_updateDomaine.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        if($_FILES['photodom']['error'] != 4){
            $photoD = upload('photodom', 'domaines/');
        }else{
            $photoD = $_POST['photoExistantedom'];
        }
        $etat = $pdo->updateDomaine($iddomaine,$nomdom,$photoD, $presentation);
        if($etat != 0){
            $message = 'La mise à jour du domaine <b>'.stripslashes($nomdom).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, le domaine <b>'.stripslashes($nomdom).'</b> n\'a pas été mis à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $domaines = $pdo->getDomaines();
        include('vues/admin/v_gestionDomaines.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $photo = $pdo->getPhoto($iddom, 'domaine');
        $vinsAssocies = $pdo->deleteDomaine($iddom);
        if($vinsAssocies){
            $dom = $pdo->getDomaine($iddom);
            include("vues/admin/v_updateDomaine.php");
        }else{
            if($photo != "images/domaines/non-dispo.jpg"){
                unlink($photo);
            }
            $message = 'Le domaine <b>'.stripslashes($nomdom).'</b> a bien été supprimé.';
            $class = 'text-success bg-success';
            $domaines = $pdo->getDomaines();
            include('vues/admin/v_gestionDomaines.php');
        }       
        break;
    }
    
}
