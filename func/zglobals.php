<?php



if(!defined('GLBAPPPORT')){
	define('GLBAPPPORT', $_SERVER['SERVER_PORT']);
}	
$foldercentral = '/webcoordinador';
//------------------------------------------------------------------------------
//Leo Archivo de Configuracion
$valDEVELOPER 	= '';
	$fileline 	= 0;
	$rutaBD 	= '';
	$passBD		= '';
	$valMD5 	= '';
	$valREG 	= '';	
	// $file = fopen($_SERVER["DOCUMENT_ROOT"]."$foldercentral/func/config.benvido", "r") or exit("Falta el Archivo de Configuracion!"); //DEV
	$file = fopen($_SERVER["DOCUMENT_ROOT"]."/func/config.benvido", "r") or exit("Falta el Archivo de Configuracion!"); //PRD

	
	while(!feof($file))
	{
		$ftext = fgets($file);		
		switch($fileline){
			case 0: 
				$rutaBD = trim($ftext);
				break;
			case 1: 
				$passBD = trim($ftext);
				break;
			case 2: 
				$valMD5 = trim($ftext);
				break;
			case 3: 
				$valREG = trim($ftext);
				break;
			case 4:				 
				$valDEVELOPER = trim($ftext);
				break;
		}
		$fileline++;
	}
	fclose($file);
	
	
	$_SESSION[GLBAPPPORT.'VALPASSREGISTRARBVDSIS'] = $valMD5;
	$_SESSION[GLBAPPPORT.'VALTEXTREGISTRARBVDSIS'] = $valREG;
	$_SESSION[GLBAPPPORT.'BDPASSBVDSIS'] = $passBD;
	$_SESSION[GLBAPPPORT.'BDPATHBVDSIS'] = $rutaBD;
	$_SESSION[GLBAPPPORT.'BDDEVELOPERBVDSIS'] = $valDEVELOPER;

//------------------------------------------------------------------------------

//ini_set('error_reporting',1); //No muestro errores producidos por PHP
define('FBuser',		'SYSDBA');

$passBDaux = '';
$BDPATHBVDSIS = '';
$VALPASSREGISTRARBVDSIS = '';
$VALTEXTREGISTRARBVDSIS = '';
if(isset($_SESSION[GLBAPPPORT.'BDPASSBVDSIS'])){
	$BDPATHBVDSIS				= $_SESSION[GLBAPPPORT.'BDPATHBVDSIS'];
	$VALPASSREGISTRARBVDSIS		= $_SESSION[GLBAPPPORT.'VALPASSREGISTRARBVDSIS'];
	$VALTEXTREGISTRARBVDSIS		= $_SESSION[GLBAPPPORT.'VALTEXTREGISTRARBVDSIS'];

	if($_SESSION[GLBAPPPORT.'BDPASSBVDSIS'] == '00'){
		$passBDaux = 'masterkey';
	}
	if($_SESSION[GLBAPPPORT.'BDPASSBVDSIS'] == '01'){
		$passBDaux = '317rino29';
	}
	if($_SESSION[GLBAPPPORT.'BDPASSBVDSIS'] == '02'){
		$passBDaux = '29mula317';
	}
	if($_SESSION[GLBAPPPORT.'BDPASSBVDSIS'] == '03'){
		$passBDaux = '29rino317';
	}
	//DEBUG de KEY
	if($_SESSION[GLBAPPPORT.'BDPASSBVDSIS'] == '99999'){
		$_SESSION[GLBAPPPORT.'BVDSISREGISTRADEBUG'] = 1;
	}
	
	if($passBDaux != ''){
		define('FBpass',		$passBDaux);
	}
}
define('FBrutadatos',	$BDPATHBVDSIS );

define('ValPassRegistra',	$VALPASSREGISTRARBVDSIS);
define('ValTextRegistra',	$VALTEXTREGISTRARBVDSIS);

// define('GLBRutaFUNC',	$_SERVER["DOCUMENT_ROOT"].$foldercentral.'/func'); //Funciones en PHP DEV
define('GLBRutaFUNC',	$_SERVER["DOCUMENT_ROOT"].'/func'); //Funciones en PHP PRD

//--------------  Colores por Defecto ---------------------
//Estilo de Selects
define('CFG_Style_Select',	' style="background-color:Silver;" ');
//---------------------------------------------------------

define('GLBFilas', 20);

if(isset($_SESSION[GLBAPPPORT.'BDDEVELOPERBVDSIS'])){
	$valDEVELOPER = $_SESSION[GLBAPPPORT.'BDDEVELOPERBVDSIS'];
	
	if($valDEVELOPER!=''){
		$arr = explode('=',$valDEVELOPER);
		if(isset($arr[1]))
			define('DEVELOPER',$arr[1]);
		else
			define('DEVELOPER',0);
	}else{
		define('DEVELOPER',0);
	}
}else{
	define('DEVELOPER',0);
}

?>
