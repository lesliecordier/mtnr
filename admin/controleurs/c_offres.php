<?php
$action = $_GET['action'];
switch($action){
    
    case 'gestion':{     
        $offres = $pdo->getOffres();
        $moisannees = $pdo->getDatesOffres();
        $mois = array(
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        );
        $message ="";
        $class = "";
        include('vues/admin/v_gestionOffres.php');
        break;
    }
    
    case 'afficherListe':{ 
        //mise à jour de la liste des offres selon select
        $where = "WHERE ";
        $tabmois = array(
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        );
        foreach($_POST as $key=>$value){
            if($value != ""){
                $date = split("-",$value);
                $mois = $date[0];
                $annee = $date[1];
                $array[$key] = $value;
                $where .= 'MONTH(date) = '.$mois.' AND YEAR(date) = '.$annee;
            }
        }
        $offresTriees = $pdo->getOffresTriees($where);
        $liste = "";
        $double = [];
        foreach($offresTriees as $titre){
            if (!isset($double[$titre['mois']][$titre['annee']])){
                $double[$titre['mois']][$titre['annee']] = true;
                $liste .= '<div class="panel panel-default">';
                $liste .= '<div class="panel-heading">'.$tabmois[$titre['mois']].' '.$titre['annee'].'</div>';
                $liste .= '<ul class="list-group">';
                foreach($offresTriees as $uneOffre){
                    if($uneOffre['mois'] == $titre['mois'] && $uneOffre['annee'] == $titre['annee']){
                        $liste .= '<li id="offre'.$uneOffre['idOffre'].'" class="list-group-item"><a href="admin.php?control=offres&action=ficheUpdate&id='.$uneOffre['idOffre'].'">'.$uneOffre['nom'].'</a></li>';
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
        include("vues/admin/v_addOffre.php");
        break;
    }
    
    case 'add':{
        foreach ($_POST as $key => $value){
                $$key = addslashes(trim($value));
        }
        if($_FILES['miniature']['error'] != 4){
            $miniature = upload('miniature', 'offres/miniatures/');
        }
        if($_FILES['img']['error'] != 4){
            $img = upload('img', 'offres/');
        }
        $date = split("-",$date);
        $dateformatee = $date[2]."-".$date[1]."-".$date[0];
        $etat = $pdo->insertOffre($nomoffre, $dateformatee, $miniature, $img);
        if($etat != 0){
            $message = "L'offre <b>".stripslashes($nomoffre)."</b> a bien été ajoutée.";
            $class = "text-success bg-success";
        }else{
            $message = 'Une erreur est survenue, l\'offre <b>'.stripslashes($nomoffre).'</b> n\'a pas été ajoutée. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $offres = $pdo->getOffres();
        $moisannees = $pdo->getDatesOffres();
        $mois = array(
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        );
        include('vues/admin/v_gestionOffres.php');
        break;
    }
    
    case 'ficheUpdate':{
        $idoffre = $_GET['id'];
        $offre = $pdo->getOffre($idoffre);
        $date = split("-",$offre['date']);
        $offre['date'] = $date[2]."-".$date[1]."-".$date[0];
        $message ="";
        $class = "";
        include("vues/admin/v_updateOffre.php");
        break;
    }
    
    case 'update':{
        foreach ($_POST as $key => $value){
                $$key = addslashes(trim($value));
        }
        if($_FILES['miniature']['error'] != 4){
            $miniature = upload('miniature', 'offres/miniatures/');
        }else{
            $miniature = $_POST['miniatureExistante'];
        }
        if($_FILES['img']['error'] != 4){
            $img = upload('img', 'offres/');
        }else{
            $img = $_POST['imgExistante'];
        }
        $date = split("-",$date);
        $dateformatee = $date[2]."-".$date[1]."-".$date[0];
        $etat = $pdo->updateOffre($idOffre, $nomoffre, $dateformatee, $miniature, $img);
        if($etat != 0){
            $message = 'La mise à jour de l\'offre <b>'.stripslashes($nomoffre).'</b> a bien été prise en compte.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, l\'offre <b>'.stripslashes($nomoffre).'</b> n\'a pas été mis à jour. Veuillez renouveler la saisie.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $offres = $pdo->getOffres();
        $moisannees = $pdo->getDatesOffres();
        $mois = array(
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        );
        include('vues/admin/v_gestionOffres.php');
        break;   
    }
    
    case 'delete':{
        foreach ($_POST as $key => $value){
            $$key = $value;
        }
        $photo = $pdo->getPhotoOffre($idOffre);
        $img = $pdo->getImg($idOffre);
        $etat = $pdo->deleteOffre($idOffre);
        //Retour au sommaire
        if($etat != 0){
            unlink($photo);
            unlink($img);
            $message = 'L\'offre <b>'.stripslashes($nomoffre).'</b> a bien été supprimée.';
            $class = 'text-success bg-success';
        }else{
            $message = 'Une erreur est survenue, l\'offre <b>'.stripslashes($nomoffre).'</b> n\'a pas été supprimée. Veuillez renouveler la suppression.';
            $class = 'text-danger bg-danger';
        }
        //Retour au sommaire
        $offres = $pdo->getOffres();
        $moisannees = $pdo->getDatesOffres();
        $mois = array(
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        );
        include('vues/admin/v_gestionOffres.php');
        break;
    }
    
}
