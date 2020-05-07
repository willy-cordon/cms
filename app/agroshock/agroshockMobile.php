<?
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
			
	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('agroshockMobile.html');		
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../imgshock/';
	$dirint = dir($pathimagenes);
	while (($archivo = $dirint->read()) !== false)
    {
		if($archivo!='..' && $archivo!='.'){
			$tmpl->setCurrentBlock('imagenes');
			$tmpl->setVariable('imgpath', $pathimagenes.$archivo);
			$tmpl->parse('imagenes');
		}
		
    }
    $dirint->close();
	
	$tmpl->show();
	
	//--------------------------------------------------------------------------------------------------------------

?>	
