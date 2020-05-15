<?
	if(!isset($_SESSION))  session_start();
	//include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('login.html');
	
	$_SESSION[GLBAPPPORT.'PERCODIGO'] = '';
	
	
	$_SESSION['success'] = true;
		
	//Nombre del Evento
	if(isset($_SESSION['PARAMETROS']['SisNombreEvento'])){
		$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);	
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
