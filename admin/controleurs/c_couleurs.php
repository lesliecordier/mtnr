<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $couleurs = $pdo->getCouleurs();
        $message ="";
        $class = "";
        include('vues/admin/v_gestionCouleurs.php');
        break;
    }
    
    case 'ficheAdd':{
        include("vues/admin/v_addCouleur.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
            $$key = addslashes(trim($value));
        }
        $etat = $pdo->insertCouleur($nomcouleur);
        if($etat != 0){
            $message = 'La couleur <b>'.stripslashes($nomcouleur).'</b> a bien été ajoutée.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la couleur <b>'.stripslashes($nomcouleur).'</b> n\'a pas été ajoutée. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $couleurs = $pdo->getCouleurs();
        include('vues/admin/v_gestionCouleurs.php');
        break;
    }
    
    case 'ficheUpdate':{
        $idcouleur = $_GET['id'];
        $couleur = $pdo->getCouleur($idcouleur);
        include("vues/admin/v_updateCouleur.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $etat = $pdo->updateCouleur($idcouleur,$nomcouleur);
        if($etat != 0){
            $message = 'La mise à jour de la couleur <b>'.stripslashes($nomcouleur).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, la couleur <b>'.stripslashes($nomcouleur).'</b> n\'a pas été mise à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        $couleurs = $pdo->getCouleurs();
        include('vues/admin/v_gestionCouleurs.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $bieresAssociees = $pdo->deleteCouleur($idcouleur);
        if($bieresAssociees){
            $couleur = $pdo->getCouleur($idcouleur);
            include("vues/admin/v_updateCouleur.php");
        }else{
            $message = 'La couleur <b>'.stripslashes($nomcouleur).'</b> a bien été supprimée.';
            $class = 'text-success bg-success';
            $couleurs = $pdo->getCouleurs();
            include('vues/admin/v_gestionCouleurs.php');
        } 
        break;
    }
    
}
