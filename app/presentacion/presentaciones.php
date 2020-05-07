<?php
	if(!isset($_SESSION))  session_start();
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('presentaciones.html');
	
	//--------------------------------------------------------------------------------------------------------------
	$valcode = substr(md5('OnLifeAccesoAppMobile'),0,10).substr(md5('MobileApp'),5,10).substr(md5('oNlIFEApplication'),8,20).md5('OnLifeAccesoApplication');
	//Ej:29b3948425293c67ac3c261ed704d436cfc0bae1f557a04ccf8ae7f0eaff5c362604addd
	$datainfo = (isset($_GET['P']))? trim($_GET['P']) : '';
	$tmpl->setVariable('datainfo'	, $datainfo);
	
	$tmpl->show();
	
?>	
