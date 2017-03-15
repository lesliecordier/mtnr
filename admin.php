<?php
session_start();
require_once("include/fonctions.php");

/**
* admin.php
*/

/** 
 * controleur global pour le backoffice
 *
 * @copyright CORDIER Leslie
 * @package controleurs
 * @author CORDIER Leslie
 * @version v 1.0
 */
include("vues/v_header.php") ;

$estConnecte = estConnecte();
if(!isset($_GET['control']) || !$estConnecte){
     $_GET['control'] = 'login';
}	 
$control = $_GET['control'];
switch($control){
	case 'login':{
		include("controleurs/c_login.php");break;
	}
	case 'import' :{
		include("controleurs/c_import.php");break;
	}
	case 'export' :{
		include("controleurs/c_export.php");break; 
	}
        case 'offres' :{
                include("controleurs/c_offres.php");break;
        }
}
include("vues/v_footer.php") ;