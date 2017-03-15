<?php
$log = 'admin';
$pass = 'admin';

if(!isset($_GET['action'])){
	$_GET['action'] = 'login';
}
$action = $_GET['action'];
switch($action){
    case 'login':{
        include("vues/v_login.php");
        break;
    }
    case 'valide':{
        if(isset($_POST['user'], $_POST['password'])) {
            $user = $_POST['user'];
            $password = $_POST['password'];
        }
        if($user == $log && $password == $pass){
            $_SESSION['connect'] = 1;
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $password;
            include('vues/v_sommaire.php');
        }
        else{
            include('vues/v_erreur.php');
        }
        break;
    }
    case 'sommaire':{
        include("vues/v_sommaire.php");
        break;
    }
    default :{
            include("vues/v_login.php");
            break;
    }
}
