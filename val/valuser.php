<?php		 
	$foldercentral = '/webcoordinador';
	ini_set( 'default_charset', 'UTF-8' );

	 //Validacion de LOGUEO de USUARIO	 
     if(!isset($_SESSION))  session_start();
	
	if(!defined('GLBAPPPORT')){
		define('GLBAPPPORT', $_SERVER['SERVER_PORT']);
	}	
	
	if(!isset($_SESSION[GLBAPPPORT.'PERCODIGO'])){ //Control de Logueo de Usuario  	 
		$_SESSION[GLBAPPPORT.'PERCODIGO'] 		= '';
				
         echo "<script> window.top.location='$foldercentral/login.html'; </script>";
         exit; 
    }else{
		if(trim($_SESSION[GLBAPPPORT.'PERCODIGO'])==''){			
			echo "<script> window.top.location='$foldercentral/login.html'; </script>";
			exit; 
		}
	}
	
     //Variables Globales
	 include($_SERVER["DOCUMENT_ROOT"].$foldercentral.'/func/zglobals.php'); //DEV
	// include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	 
             
     //----------------------------------------------------------------------------------------------
	//Valido KEY
	require_once "valkey.php";
	if(ValKey() != 'BENVIDO10450420SISTEMAS'){		
		echo "Error de Registracion!";
		exit;
	}
