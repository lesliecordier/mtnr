<?php

function estConnecte(){
    return isset($_SESSION['connect']);
}


function detailErreur($file){
    switch($_FILES[$file]['error']){
        case 1 : $message = 'Erreur : la taille du fichier dépasse la capacité autorisée par le serveur.';
        break;
        case 2 : $message = "La taille de l'image est trop importante (taille max : 3 Mo).";
        break;
        case 3 : $message = "Le fichier n'a été que partiellement téléchargé.";
        break;
        case 6 : $message = "Le dossier temporaire est manquant.";
        break;
        case 7 : $message = "Echec de l'écriture du fichier sur le disque.";
        break;
        case 8 : $message = "Une extension PHP a arreté l'envoi du fichier.";
        break;
    }
    return $message;
}

function upload($file, $dir){

    if($_FILES[$file]['error'] != 4) {
        $name = basename($_FILES[$file]['name']);
        $doc = "../" . $dir . $name;
        $tmp = $_FILES[$file]["tmp_name"];
        
        if($_FILES[$file]['error'] > 0){
            detailErreur($file);
                
        // Si $upload ok
        } else if (move_uploaded_file($tmp, $doc)) {
            return "Le fichier $name a bien été téléchargé !";
        } else {
            return false;
        }
        
    }else{
        return false;
    }
}
