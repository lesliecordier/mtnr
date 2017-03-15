<?php
$action = $_GET['action'];
switch($action){
    
    case 'sommaire':{     
        include('vues/v_sommaireVins.php');
        break;
    }
    
    case 'xls':{
        if ($_FILES['import-xls']['error'] != 4 && $_FILES['import-xls']['type'] == 'application/vnd.ms-excel'){
            $_FILES['import-xls']['name'] = 'vins.xls';
            $message1 = upload('import-xls', '');
            if (!$message1){
                $erreur1 = "Le téléchargement a échoué. Assurez-vous que le fichier est bien au format .xls.";
            }
        }else{
            $erreur1 = 'Le téléchargement a échoué. Assurez-vous que le fichier est bien au format .xls.';
        }
        include('vues/v_sommaire.php');
        break;
    }
    
    case 'doc':{
        if ($_FILES['import-doc']['error'] != 4 && ($_FILES['import-doc']['type'] == 'application/pdf' || $_FILES['import-doc']['type'] == 'image/jpeg')){
            $message2 = upload('import-doc', 'docs/');
            if (!$message2){
                $erreur2 = "Le téléchargement a échoué. Assurez-vous que le fichier est bien au format .pdf ou .jpeg.";
            }
        }else{
            $erreur2 = 'Le téléchargement a échoué. Assurez-vous que le fichier est bien au format .pdf ou .jpeg.';
        }
        include('vues/v_sommaire.php');
        break;
    }
}